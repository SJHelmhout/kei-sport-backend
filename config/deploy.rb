# config valid for current version and patch releases of Capistrano
lock "~> 3.16.0"

set :application, "kei-sport-backend"
set :repo_url, "git@github.com:SJHelmhout/kei-sport-backend.git"

set :symfony_directory_structure, 4
set :sensio_distribution_version, 6
set :web_path, "public"

set :linked_files, [".env.local"]
#append :linked_files, "assets/js/.env"
append :linked_dirs, "config/jwt"

set :yarn_roles, :web
set :yarn_flags, '--silent --no-progress'

after "yarn:install", "yarn:build"
# after "yarn:build", "yarn:sapphire_install"


