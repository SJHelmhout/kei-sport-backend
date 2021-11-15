namespace :deploy do
  task :migrate do
    on roles(:db) do
      within release_path do
        symfony_console('doctrine:migrations:migrate', '--no-interaction')
      end
    end
  end
end