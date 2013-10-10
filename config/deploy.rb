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
set :linked_dirs, %w{htdocs/uploads}

# set :default_env, { path: "/opt/ruby/bin:$PATH" }
set :keep_releases, 5

namespace :deploy do
  desc "Overwrite the start task because symfony doesn't need it."
  task :starting do ; end

  desc "Overwrite the restart task because symfony doesn't need it."
  task :restart do ; end

  desc "Overwrite the stop task because symfony doesn't need it."
  task :stoping do ; end

  desc "This task is the main task of a deployment."
  task :updating do ; end
  
  before :finishing, 'symfony:permissions'
  before :finishing, 'symfony:yigg:build'

  after :finishing, 'deploy:cleanup'
end

namespace :symfony do
  desc "Clear the cache."
  task :permissions do
    on roles(:all) do
      execute "mkdir -p #{current_path}/cache"
      execute "mkdir -p #{current_path}/log"
      execute "php #{current_path}/symfony project:permissions"
    end
  end

  desc "Clear the cache."
  task :cc do
    on roles(:all) do
      execute "php #{current_path}/symfony cc --env=#{fetch(:stage)}"
    end
  end

  desc "Disable the app."
  task :disable do
    on roles(:all) do
      execute "php #{current_path}/symfony project:disable #{fetch(:stage)}"
    end
  end

  desc "Enable the app."
  task :enable do
    on roles(:all) do
      execute "php #{current_path}/symfony project:enable #{fetch(:stage)}"
    end
  end

  namespace :yigg do
    desc "Build it."
    task :build do
      on roles(:all) do
        execute "php #{current_path}/symfony yigg:build --all --env=#{fetch(:stage)} --no-confirmation"
      end
    end
  end
end