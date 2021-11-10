namespace :deploy do
  task :migrate do
    on roles(:db) do
      within release_path do
        symfony_console('app:safe-migrate')
      end
    end
  end
end
