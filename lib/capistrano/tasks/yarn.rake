namespace :yarn do
  desc "yarn build"
  task :build do
    on roles fetch(:yarn_roles) do
      within fetch(:yarn_target_path, release_path) do
        with fetch(:yarn_env_variables, {}) do
          execute fetch(:yarn_bin), 'build --no-progress'
        end
      end
    end
  end

  desc "yarn sapphire install"
  task :sapphire_install do
    on roles fetch(:yarn_roles) do
      within fetch(:yarn_sapphire_target_path, release_path) do
        with fetch(:yarn_env_variables, {}) do
          execute fetch(:yarn_bin), 'install', fetch(:yarn_flags)
        end
      end
    end
  end

  desc "yarn sapphire build"
  task :sapphire_build do
    on roles fetch(:yarn_roles) do
      within fetch(:yarn_sapphire_target_path, release_path) do
        with fetch(:yarn_env_variables, {}) do
          execute fetch(:yarn_bin), 'build --no-progress'
        end
      end
    end
  end

  #after "yarn:install", "yarn:build"
  # after "yarn:build", "yarn:sapphire_install"
  # after "yarn:sapphire_install", "yarn:sapphire_build"
  #before "deploy:symlink:release", "symfony:assets:install"
end
