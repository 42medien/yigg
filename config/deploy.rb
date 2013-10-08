set :application, 'yigg'
set :repo_url, 'git@github.com:ekaabo/yigg.git'

set :branch, 'main'

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
      #symfony.yiid.build
      #symlink
    end
  end

  after :finishing, 'deploy:cleanup'

end