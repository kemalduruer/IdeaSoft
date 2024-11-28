# IdeaSoft Project

Access the complete project on GitHub [here](https://github.com/ideasoft/se-take-home-assessment/tree/master).

## Initial Setup

To get started on the application setup, use the Docker Compose instruction:
>docker-compose up --build

## Environment Configuration
The project runs within a Docker environment. The following components are installed within the container:

- PHP Version: 7.4
- MySQL Version: 8.3
- Laravel 7.30.7
- Composer

Upon running the desk, `dump.sql` will be automatically added to the DB container.

## API Endpoints Overview

Interact with the project API by importing the `Postman collection`. The following endpoints are available for use:

### Customers Management API
- **List Customers Products:** `GET http://localhost:8080/customers`
- **Get Customers Detail:** `GET http://localhost:8080/customers/{id}`

### Product Management API
- **List All Products:** `GET http://localhost:8080/api/products`
- **Get Product Detail:** `GET http://localhost:8080/api/products/{id}`

### Order Management API
- **List All Orders:** `GET http://localhost:8080/api/orders`
- **Get Order Detail:** `GET http://localhost:8080/api/orders/{order_id}`
- **Delete an Order:** `DELETE http://localhost:8080/api/orders/{order_id}`
- **Add an Order:** `POST http://localhost:8080/api/orders`


     To add an order, post the form data in the following format:

    {
        "customer_id": 1,
        "items": [
          {
            "productId": 100,
            "quantity": 50
          },
          {
            "productId": 102,
            "quantity": 8
          },
          {
            "productId": 103,
            "quantity": 10
          }
       ]
     }

    You can use the received {orderId} parameter with the Discount Management API.

### Discount Management API
- **Get Discount for Order:** `POST http://localhost:8080/api/calculate-discounts`

    
      To add an order, post the form data in the following format:

    {
        "orderId" : {orderId}
    }

   
