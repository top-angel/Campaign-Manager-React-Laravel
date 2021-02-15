# READ ME

Hey there, hope you're doing well here I'm submitting the work I've decided as per your requirement kindly check the below steps to deploy the same on your machine or server

# SETTING UP LARAVEL BACKEND
1. From the backend directory, hit the command `composer install`
2. Now as i've followed each and every laravel community standards you can see there will be few `migrations` and `seeders` to seed dummy data
3. After extracting hit the command `php artisan key:generate` , then `php artisan migrate --seed` which will migrate and seed the project at the same time
4. Now i've also attached a postman collection file you can import and run the apis but make sure to change the `base_url`
5. Also don't forgot to add your specific environment configuration in `.env` file
6. Once everything set up , you can open postman and check each and every api's response and code

# SETTING UP FRONTEND
1. From the frontend directory, hit the command `npm i`
2. It will install or required packages and then hit `npm run start` to start the application

