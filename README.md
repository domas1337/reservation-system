# Reservation system includes

## Simple login system
- Checks for correct usage of inputs such as Name, Surname, Email and Phone number.
- Uses PHP sessions ($\_SESSION['']) as sessions.
- Header contains a check for enabled condition doing a query to the database.

## Role system
- Junior, Controller and Admin roles have different permissions.
- Admin can edit, add and view everything stored on the website/database such as users and their information and reservations(can't edit) and can do what other roles can
- Controller can view documents and verify reservations
- Junior can verify reservations

## Reservation system
- Check-in and check-out date
- Stores verified reservations in documents with references to user, room and price at that time.


# Initial information
Implement web application to receive reservation requests and validate or decline them.

Two different UI Interface, one for the guest accounts and one for the company accounts that administrate requests.

 

The guest account can only select the period of reservation, the room and do a request to cancel a previous reservation if this will start minimum two days after the cancellation date.

The start reservation starts always at 12:00 and ends at 10:00.

 

The company accounts are divided in 3 level:

- Admin can be do all (accept and decline reservation, create/modify/disabled accounts, etc.)

- Controller can be accept and decline reservation, see the clients data(no password)

- Junior can control the state of reservation and see the clients data(no password, no document id)

 

All users, Admin, Controller, Junior and Guest have same data:

- Name

- Surname

- Email

- Password

- Enabled

- Contacts

- Document ID

 

 

Specifics:

- Database => MySQL

- Frontend => HTML5 + CSS3 + any javascript framework do you want

- Backend => PHP 5

- Apache => 2.x

- Target device => Desktop (from 1280x1024 to FullHD), Tablet (10", 8", 6"), Smartphone (6.5", 6", 5.5", 5", 4.5", 4")

- S.O. Server => CentOS 7.x

 

Evaluation parameters:

- Correct choice of the design pattern and it implementation

- Software strength (correct exception management, security policy, etc.)

- Comments readability and completeness (Doxygen standard)

- Software extension possibility

- Installation document

 

The software description and specifications are not complete because we would like to see how  would you choose some solutions rather than others

 

P.S. The graphic aspect is not important, but usability yes 