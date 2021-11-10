namespace :env do
   desc "Copy files"
   task :copy do
      on roles(:all) do
        info "Copying to #{shared_path}/.env.local"
        upload! fetch(:env_file, '.env.local'), "#{shared_path}/.env.local"
      end
   end
end

before "env:copy", "deploy:check:directories"
