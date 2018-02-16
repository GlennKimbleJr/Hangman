# Installation Instructions
```
    git clone https://github.com/GlennKimbleJr/hangman.git Hangman
    cd Hangman
    cp .env.example .env
    composer install
    npm install
    php artisan key:generate
    update DB_DATABASE, DB_USERNAME, and DB_PASSWORD in .env
    php artisan migrate --seed
```

Login as demo@example.org | demo