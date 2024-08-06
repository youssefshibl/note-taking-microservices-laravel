![Untitled-2023-08-22-1534](https://github.com/user-attachments/assets/ae33a215-d030-4939-8c22-9572b0ff984b)

## 🚀 Microservices Based Note Taking Application

#### ⏳ description
This is a simple note taking application that is built using microservices architecture. The application consists of two services, the `notes-service` and the `users-service`. The `notes-service` is responsible for managing notes and the `users-service` is responsible for managing users and authentication. The `notes-service` and the `users-service` communicate with each other using REST APIs And message broker (RabbitMQ) , every Microservice has its own database and two services are connected to Nginx as a api gateway for routing the requests.


---
#### 🚀 How to run the Infrastructure
- Clone the repository
- Run `start.sh` script to start the application the flow of script 
    - Check if docker is installed or not
    - Check if docker-compose is installed or not
    - Start the application using docker-compose
    - Check if the application is started successfully or not by check status code of end point of nginx getway
- docker compose will start the following services
    - `users-service-db` : MySQL database for the `users-service`
    - `notes-service-db` : MySQL database for the `notes-service`
    - `auth-service` : Laravel service for user management and authentication
    - `notes-service` : Laravel service for notes management
    - `notes-service-worker` : Laravel worker service for handling delete user event
    - `nginx` : Nginx service as an API gateway
    - `rabbitmq` : RabbitMQ service as a message broker

---
# 🔦 [Postman API Documentation](https://documenter.getpostman.com/view/20246655/2sA3rxqDNp)

+ path: ApiGetway + Microservice + Route

#### Auth Service
- Create User : APIGetway + '/auth/api/register' [POST]
- Login User : APIGetway + '/auth/api/login' [POST]
- Check Authenticated User : APIGetway + '/auth/api/check' [GET]
- Delete User : APIGetway + '/auth/api/delete' [DELETE]

#### Notes Service
- Create Note : APIGetway + '/note/api/note' [POST]
- Get All Notes : APIGetway + '/note/api/note' [GET]
- Get Single Note : APIGetway + '/note/api/note/{id}' [GET]
- Update Note : APIGetway + '/note/api/note/{id}' [PUT]
- Delete Note : APIGetway + '/note/api/note/{id}' [DELETE]


####  📝 Features
- User registration , login and authentication using JWT
- Create, Read, Update and Delete notes
- Get all notes of a user
- Get a single note of a user

---

####  ⚡ Flow of the application
- User registers throw the `auth-service` Service and gets a JWT token
- User Creates a note throw the `notes-service` Service and sends the JWT token in the header
- The `notes-service` Service validates the JWT token Throw send REST API request to the `auth-service` Service
- The `auth-service` Service validates the JWT token and sends the response to the `notes-service` Service
- The `notes-service` Service creates the note in the database and returns the response to the user
- If User deletes his account throw the `auth-service` Service, `auth-service` Service sends an event to the `notes-service` Service to delete all notes of the user throw `RabbitMQ` message broker

#### 🤿 Important questions ?
#####  Why we don't use a message broker to communicate between the `auth-service` and the `notes-service` in authentication process ?
- Because `note-service` needs to validate the JWT token in every request and it's not a good idea to use a message broker in this case because it will make the process slower and more complex because `note-service` want to response to the user as fast as possible so if we use a message broker it will make the process slower because the `note-service` will send event contains the JWT token and identification number or session id to identify current user and will waint time untill the `auth-service` validate the JWT token and send the response to the `note-service` throw the message broker and then the `note-service` must in this case to validate all events that it receives from the `auth-service` throw the message broker and check which event is related to the current user and then it will respond to the user so it's not a good idea to use a message broker in this case, 
![Untitled-2023-08-22-15341](https://github.com/user-attachments/assets/3f4a16dd-3b20-401c-accf-03edf46feb2c)
let us take example to worst case we have 4 requested in same time so php application server like (fpm-php or FrankenPHP ) open 4 threads to handle the requests and the 4 threads will send 4 events to the message broker and the message broker will send the 4 events to the `auth-service` and the `auth-service` will validate the JWT token and send the response to the message broker and the message broker will send the response to the `note-service` and the `note-service` will validate the response and send the response to the user so in this case the process will be slower and more complex and the `note-service` will validate the response 4 times to check which response is related to the current user so it's not a good idea to use a message broker in this case in this case service or instance to service is stateful because the `note-service` must validate the response and check which response is related to the current user so it's not a good idea to use a message broker in this case


#####  Why we use a message broker to communicate between the `auth-service` and the `notes-service` in delete user process ?

- Because `auth-service` wants to delete all notes of the user when the user deletes his account so it's a good idea to use a message broker in this case because the `auth-service` doesn't need to wait for the response of the `notes-service` to delete the user because the `auth-service` doesn't need to know if the `notes-service` deleted the user notes or not so it's a good idea to use a message broker in this case because the `auth-service` can send the event to the message broker and the message broker will send the event to the `notes-service` and the `notes-service` will delete the user notes and the `notes-service` doesn't need to send a response to the `auth-service` so it's a good idea to use a message broker in this case because the `auth-service` doesn't need to wait for the response of the `notes-service` to delete the user notes so in this case service or instance to service is stateless because the `auth-service` doesn't need to wait for the response of the `notes-service` to delete the user notes so it's a good idea to use a message broker in this case

### 🧪 Testing
We make Feature tests for the `auth-service` and the `notes-service` to test the API endpoints and the authentication process and the notes management process

##### Auth Service
- Register User
- Login User
- Check Authenticated User
- Delete User

How to run the tests
- Go to the `auth-service` directory
- Run `php artisan test`

##### Notes Service
- Create Note
- Get All Notes
- Get Single Note
- Update Note
- Delete Note

How to run the tests
- Go to the `notes-service` directory
- Run `php artisan test`

### 🔬 Testing Pipeline using Github Action

i make a Github Action workflow to run the tests of the `auth-service` and the `notes-service` every time we pull request to the main branch , i make separate testing pipline workflow for the `auth-service` and the `notes-service` to run the tests of the `auth-service` and the `notes-service` separately based on the changes that we made in the code of the `auth-service` or the `notes-service` so we can know which service has the issue in the code and we can fix it easily

![Screenshot from 2024-08-05 23-51-54](https://github.com/user-attachments/assets/9286b828-4b2a-43ab-ba17-c2c56fcdaf24)
![Screenshot from 2024-08-05 23-53-12](https://github.com/user-attachments/assets/2b9bec6e-6231-4869-9c97-05a46ceec71c)
![Screenshot from 2024-08-05 23-59-22](https://github.com/user-attachments/assets/7976738f-757f-493a-8489-5e175fcd67cf)
![Screenshot from 2024-08-05 23-59-49](https://github.com/user-attachments/assets/c9891e24-e02f-45b4-9708-0d28e0b39925)



### 💉 Future Plans

- Add more features to the application like sharing notes between users
- Add end to end testing to the application whcih test the application as a whole in every new release
- Add more microservices to the application like `files-service` to manage files and `notifications-service` to manage notifications
- Scale the application by adding more instances of the services and use a load balancer to distribute the requests between the instances
- Use Kubernetes to manage the application and scale the application and make it more reliable and secure
- Use Redis as a cache to cache the requests and make the application faster




### 🛠️ Technologies
- Laravel
- MySQL
- RabbitMQ
- Nginx
- Docker
- Github Actions
