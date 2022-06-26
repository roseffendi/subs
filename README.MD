### Run project locally
To run this project locally please follow this steps

 - Copy `.env.example` to `.env`
 - Run `docker-compose up -d`
 - Run `./be php artisan migrate` to migrate the database.
 - Run `./be php artisan db:seed` if you want to seed the database.
 - Then, from your browser visit `http://localhost:3000/`to access the app.