# Kanban To-Do Application

Basic To-Do application developed with [Laravel](https://laravel.com), [TypeScript](https://www.typescriptlang.org/), [Vue](https://vuejs.org/)

## Installation

1. Clone the repository
```
    git clone https://github.com/alexisbogado/todo-app.git
```

2. Install required libraries **(Make sure that you have at least PHP 7.4 in order to install the libraries successfully)**
```
    cd todo-app
    composer install
```

3. Rename .env.example file to .env and configure with your database credentials or make a copy of it (NOTE: You must change the value of `APP_URL` before compile the front-end assets)
```
    cp .env.example .env
```

4. Generate application keys
```
    php artisan key:generate
    php artisan jwt:secret
```

5. Run tests to check that everything works well (NOTE: Make sure you have extension pdo_sqlite enabled in order to execute the tests)
```
    php artisan test
```

6. Run migrations
```
    php artisan migrate
```

7. Run database seeders
```
    php artisan db:seed
    -----
    This command will create a user with the following credentials,
    as well as the needed data to run the application successfully:
    
    - Email: product@manager.com
    - Password: admin
```

8. Install front-end dependencies
```
    yarn|npm install
```

9. Compile ts/sass files
```
    yarn dev|prod|watch|hot
```


## Stack
- [PHP 7.4](https://php.net/)
- [Laravel](https://laravel.com/)
- [JWT Auth](https://github.com/tymondesigns/jwt-auth/)
- [PHPUnit](https://phpunit.de/)
- [TypeScript](https://www.typescriptlang.org/)
- [Vue](https://vuejs.org/)
- [Vuex](https://vuex.vuejs.org/)
- [Vue Router](https://router.vuejs.org/)
- [Vue Draggable](https://github.com/SortableJS/Vue.Draggable#readme)
- [Axios](https://github.com/axios/axios)
- [SASS](https://sass-lang.com/)

## Cooming soon
- [ ] Roles on boards
- [ ] Write front-end tests
