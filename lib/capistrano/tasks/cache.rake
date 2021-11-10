namespace :cache do
  task :opcache_reset do
    on roles(:web) do
      execute "php #{release_path}/vendor/gordalina/cachetool/bin/cachetool opcache:reset --fcgi"
    end
  end

  task :apache_graceful do
    on roles(:web) do
      execute "sudo apache2ctl graceful"
    end
  end

  task :fpm_reload do
    on roles(:web) do
      execute "sudo systemctl reload php7.3-fpm.service"
    end
  end
end
