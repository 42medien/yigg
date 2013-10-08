default_run_options[:pty] = true
set :application, "yigg"
set :use_sudo,    false

set :repository,  "git@github.com:ekaabo/yigg.git"
set :scm,         "git"
set :user,        "spreadly"
set :scm_passphrase, "affen2010"
set :branch,      "master"

role :web,        "yigg.de"                       # Your HTTP server, Apache/etc
set :current_dir, "current"
ssh_options[:forward_agent] = true

set :keep_releases,  5

task :staging do
  set :sf_env,    "staging"
  set :domain,    "staging.yigg.de"
  set :deploy_to, "/mnt/data/yigg/www/httpdocs"
  set :deploy_via, :checkout
  puts "Deploying #{application} to #{domain} for env=#{sf_env} …"
end

# Symfony stuff

before "deploy:symlink"
after "deploy:symlink"


# Dirs that need to remain the same between deploys (shared dirs)
set :shared_children,   %w(log web/uploads)

namespace :deploy do
  desc "Overwrite the start task because symfony doesn't need it."
  task :start do ; end

  desc "Overwrite the restart task because symfony doesn't need it."
  task :restart do ; end

  desc "Overwrite the stop task because symfony doesn't need it."
  task :stop do ; end

  desc "Overwrite the migrate task because symfony doesn't need it."
  task :migrate do ; end

  desc "We do not need to restart anything, so it was taken out."
  task :default do
    update
  end

  desc "This task is the main task of a deployment."
  task :update do
    transaction do
      update_code
      symfony.yiid.build
      symlink
    end
  end

  desc "Symlink static directories and static files that need to remain between deployments."
  task :share_childs do
    if shared_children
      shared_children.each do |link|
        run "mkdir -p #{shared_path}/#{link}"
        run "if [ -d #{release_path}/#{link} ] ; then rm -rf #{release_path}/#{link}; fi"
        run "ln -nfs #{shared_path}/#{link} #{release_path}/#{link}"
      end
    end
  end

  desc "Customize the finalize_update task to work with symfony."
  task :finalize_update, :except => { :no_release => true } do
    run "mkdir -p #{latest_release}/cache"
    run "mkdir -p #{latest_release}/log"
    run "chmod -R 777 #{latest_release}/cache"
    run "chmod -R 755 #{latest_release}/log"

    # Share common files & folders
    share_childs
  end

  desc "Need to overwrite the deploy:cold task so it doesn't try to run the migrations."
  task :cold do ; end

end

namespace :symfony do

  desc "Clear the cache."
  task :cc do
    run "php #{latest_release}/symfony cc --env=#{sf_env}"
  end

  desc "Disable the app."
  task :disable do
    run "php #{latest_release}/symfony project:disable #{sf_env}"
  end

  desc "Enable the app."
  task :enable do
    run "php #{latest_release}/symfony project:enable #{sf_env}"
  end

  namespace :yigg do
    desc "Build it."
    task :build, :roles => :web do
      command = "php #{latest_release}/symfony yigg:build --all --env=#{sf_env} --no-confirmation"

      do_it = Capistrano::CLI.ui.ask("Do you really want to do this:\n#{command}\nAnswer with (y|n)[n]: ")

      if do_it=='y'
        run command
      else
        puts "Skipping it"
      end
    end
  end
end

# Runs +command+ as root invoking the command with su -c
# and handling the root password prompt.
#
#   surun "/etc/init.d/apache reload"
#   # Executes
#   # su - -c '/etc/init.d/apache reload'
#
def surun(command)
  password = fetch(:root_password, Capistrano::CLI.password_prompt("root password: "))
  run("su - -c '#{command}'", :options => {:pty => true}) do |channel, stream, output|
    channel.send_data("#{password}n") if output
  end
end