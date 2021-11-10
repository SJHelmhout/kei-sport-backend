namespace :supervisor do
  task :restart do
    on roles(:app) do
      execute 'sudo /usr/bin/supervisorctl restart cobalt-messenger:*'
    end
  end

  task :stop do
    on roles(:app) do
      execute 'supervisorctl stop messenger-consume:*'
    end
  end

  task :start do
    on roles(:app) do
      execute 'supervisorctl start messenger-consume:*'
    end
  end
end


