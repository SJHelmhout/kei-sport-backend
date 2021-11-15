server "kei-sport-backend.kei.dev", user: "vwp", roles: %w{app db web}, ssh_options: { host_name: '192.168.1.12' }
set :deploy_to, "/var/www/vhosts/kei-sport-backend"
set :keep_releases, 2
set :symfony_env, "prod"
set :env_file, '.env.kei.local'

set :permission_method,     :acl
set :file_permissions_users, ['www-data']
set :file_permissions_paths, ['var']
set :assets_install_path, "public"

set :composer_install_flags, "--no-dev --prefer-dist --no-interaction --optimize-autoloader"
set :composer_download_url, "https://getcomposer.org/installer"
set :composer_version, '2.1.12'

after "deploy:publishing", "cache:fpm_reload"
after "deploy:published", "deploy:migrate"

SSHKit.config.command_map[:composer] = "php #{shared_path.join("composer.phar")}"

namespace :deploy do
  after :starting, 'composer:install_executable'
end

set :default_env, {
    'APP_ENV' => 'prod',
}
