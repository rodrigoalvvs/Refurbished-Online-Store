# Refurbished 



## Group ltw06g07

- David Tavares Simões (up202210329) 45%
- Rodrigo Ferreira Alves (up202207478) 45%
- Vanessa Barros (up202311552) 10%


## Install Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw06g07.git refurbished
   cd refurbished
2. **Set up database**
    ```bash
   cd database
   sqlite3 users.db < users.sql

3. **Run PHP server**

## External Libraries

We have used the following external libraries:



- FontAwesome
  
## Screenshots
![mainpage](https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw06g07/blob/master/docs/screenshots_/mainpage.png?raw=true)
![addProduct](https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw06g07/blob/master/docs/screenshots_/add.png?raw=true)
![productPage](https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw06g07/blob/master/docs/screenshots_/product.png?raw=true)


## Implemented Features



**General**:



- [X] Register a new account.

- [X] Log in and out.

- [X] Edit their profile, including their name, username, password, and email.



**Sellers**  should be able to:



- [X] List new items, providing details such as category, brand, model, size, and condition, along with images.

- [X] Track and manage their listed items.

- [X] Respond to inquiries from buyers regarding their items and add further information if needed.

- [X] Print shipping forms for items that have been sold.



**Buyers**  should be able to:



- [X] Browse items using filters like category, price, and condition.

- [X] Engage with sellers to ask questions or negotiate prices.

- [X] Add items to a wishlist or shopping cart.

- [X] Proceed to checkout with their shopping cart (simulate payment process).



**Admins**  should be able to:



- [X] Elevate a user to admin status.

- [X] Introduce new item categories, sizes, conditions, and other pertinent entities.

- [X] Oversee and ensure the smooth operation of the entire system.



**Security**:

We have been careful with the following security aspects:



- [X] **SQL injection**

- [X] **Cross-Site Scripting (XSS)**

- [X] **Cross-Site Request Forgery (CSRF)**



**Password Storage Mechanism**: md5 / sha1 / sha256 / hash_password&verify_password

**Aditional Requirements**:



We also implemented the following additional requirements (you can add more):



- [ ] **Rating and Review System**

- [ ] **Promotional Features**

- [ ] **Analytics Dashboard**

- [ ] **Multi-Currency Support**

- [ ] **Item Swapping**

- [ ] **API Integration**

- [ ] **Dynamic Promotions**

- [ ] **User Preferences**

- [ ] **Shipping Costs**

- [ ] **Real-Time Messaging System**
