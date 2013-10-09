set :application, 'yigg'
set :repo_url, 'git@github.com:ekaabo/yigg.git'

set :branch, 'master'

set :deploy_to, '/mnt/data/yigg/www/httpdocs'
set :scm, :git
set :user, "spreadly"
set :scm_passphrase, "affen2010"

set :format, :pretty
set :log_level, :debug
set :pty, true

set :forward_agent, true

# set :linked_files, %w{config/database.yml}
# set :linked_dirs, %w{bin log tmp/pids tmp/cache tmp/sockets vendor/bundle public/system}

# set :default_env, { path: "/opt/ruby/bin:$PATH" }
set :keep_releases, 5

namespace :deploy do
  desc "Overwrite the start task because symfony doesn't need it."
  task :starting do ; end

  desc "Overwrite the restart task because symfony doesn't need it."
  task :restart do ; end

  desc "Overwrite the stop task because symfony doesn't need it."
  task :stoping do ; end

  desc "We do not need to restart anything, so it was taken out."
  task :default do
    update
  end

  desc "This task is the main task of a deployment."
  task :updating do
      #update_code
      #symfony.yigg.build
      #symlink
  end

  after :finishing, 'deploy:cleanup'

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
    task :build do
      command = "php #{latest_release}/symfony yiid:build --all --env=#{sf_env} --no-confirmation"

      do_it = Capistrano::CLI.ui.ask("Do you really want to do this:\n#{command}\nAnswer with (y|n)[n]: ")

      if do_it=='y'
        run command
      else
        puts "Skipping it"
      end
    end
  end
end