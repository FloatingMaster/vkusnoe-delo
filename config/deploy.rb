set :application, "vkusnoe-delo"
set :repository,  "git://github.com/flyingleafe/vkusnoe-delo.git"

set :scm, :git # You can set :scm explicitly or Capistrano will make an intelligent guess based on known version control directory names
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `git`, `mercurial`, `perforce`, `subversion` or `none`

server "vkusnoe-delo.ru", :app, :web, :db, :primary => true

set :user, "art"
set :deploy_to, "/srv/www/vkusnoe-delo.ru"
set :password, "njxysqDscnhtk1"
set :via, "scp"
set :branch, "master"
set :deploy_via, :remote_cache

default_run_options[:pty] = true

#role :web, "vkusnoe-delo.ru"                          # Your HTTP server, Apache/etc
#role :app, "your app-server here"                          # This may be the same as your `Web` server
#role :db,  "your primary db-server here", :primary => true # This is where Rails migrations will run
#role :db,  "your slave db-server here"

# if you want to clean up old releases on each deploy uncomment this:
# after "deploy:restart", "deploy:cleanup"

# if you're still using the script/reaper helper you will need
# these http://github.com/rails/irs_process_scripts

# If you are using Passenger mod_rails uncomment this:
# namespace :deploy do
#   task :start do ; end
#   task :stop do ; end
#   task :restart, :roles => :app, :except => { :no_release => true } do
#     run "#{try_sudo} touch #{File.join(current_path,'tmp','restart.txt')}"
#   end
# end

# Переопределяем настройки для PHP
namespace :deploy do
    task :start do
    end
    task :stop do
    end
    task :restart do
    end
    task :finalize_update do
    end
  end