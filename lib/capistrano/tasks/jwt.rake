namespace :jwt do
   desc "Copy files"
   task :generate do
      on roles(:all) do
        info "Generating JWT keys"
        execute "mkdir -p #{shared_path}/config/jwt"
        execute "grep '^JWT_PASSPHRASE=' #{release_path}/.env | cut -f 2 -d '=' | openssl genpkey -out #{shared_path}/config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096"
        execute "grep '^JWT_PASSPHRASE=' #{release_path}/.env | cut -f 2 -d '=' | openssl pkey -in #{shared_path}/config/jwt/private.pem -passin stdin -out #{shared_path}/config/jwt/public.pem -pubout"
      end
   end
end

before "jwt:generate", "deploy:check:directories"
