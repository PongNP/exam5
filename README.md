# install the requirement
1. install Lastest version of **XAMPP** - https://www.apachefriends.org/index.html
     - Requirement Components
          - Apache
          - PHP
          - Perl
          
     ![](https://i.imgur.com/qWVGG6F.png)
     - Directory XAMPP : `C:\xampp`
     
     ![](https://i.imgur.com/DSeZzXN.png)
     - Select a lanuage
     
     ![](https://i.imgur.com/kwKKjOz.png)
     - Bitnami you either install or not.
     
     ![](https://i.imgur.com/93fP6lz.png)
2. install Lastest version of **PostgreSQL** -  https://www.postgresql.org/
     - password master is `1234`
     - port is `5432`
3. install **PostGIS**
     - run `Application Stack Builder`<br>
     ![](https://i.imgur.com/jlE7N9J.png)
     - Select your PostgreSQL DB and click `Next`<br>
     ![](https://i.imgur.com/LWgaprE.png)
     - Check `PostGIS Bundle...` and click `Next`<br>
     ![](https://i.imgur.com/t8KU4PS.png)
     - Select your download directory & click `Next`<br>
     ![](https://i.imgur.com/VydlZRk.png)
     - After Download a setup file, Uncheck `skip Installation` and click `Next`<br>
     ![](https://i.imgur.com/327MmWM.png)
     - Choose `PostGIT` only and click `next`<br>
     ![](https://i.imgur.com/ESvQZPT.png)
     - Select a path of PostgreSQL and click `next`<br>
     ![](https://i.imgur.com/KF51wEI.png)
     - This pop up click 'Yes'<br>
     ![](https://i.imgur.com/GEyedQA.png)<br>
     ![](https://i.imgur.com/RxY1KsA.png)<br>
     ![](https://i.imgur.com/NfJiSRg.png)
     - After PostGIS, You back to Application Stack Builder windows and click `finish` button
4. Install **composer**
5. Install **Git tool**
# Setup & Config
1. Create a DB
     - Open `pgAdmin4` for run PostgreSQL server.<br>
     ![](https://i.imgur.com/9DGky9B.png)
     - Open a browser and goto pgAdmin windows.<br>
     ![](https://i.imgur.com/y5br1Pz.png)
     - Create a Database
          - Right click on `Databases`, then click on `create >> database...`.
          - Databese name is `exam5`, then click a `save` button.<br>
     ![](https://i.imgur.com/pUz0fDx.png)<br>
     ![](https://i.imgur.com/vkDrGOL.png)<br>
     - Add Extension `PostGIS`<br>
          - Right click on `Extensions` in `exam5`, then click on `create >> Extension...`.
          - Choose `postgis` in name and click a `save` button.<br>
     ![](https://i.imgur.com/MciEPEi.png)<br>
     ![](https://i.imgur.com/akC8vLU.png)<br>
2. Allow `upload_max_filesize` & `post_max_size` can get a large file
     - set `upload_max_filesize` & `post_max_size` to `256M` in `{path_of_XAMPP}\php\php.ini`.
3. Enable PHP-extentions : `pdo_pgsql` & `pgsql`
     - remove semicolon on this line :
          - `;extension=pgsql`
          - `;extension=pdo_pgsql`
     in `{path_of_XAMPP}\php\php.ini`. like this:<br>
     ![Imgur](https://i.imgur.com/SbdSPDB.png)<br>
     and save a `php.ini` file.

# How to run a code : 
1.Run a apache local server via `XAMPP contorl Panel`<br>
     ![Imgur](https://i.imgur.com/2SK2om3.png)<br>
     ![Imgur](https://i.imgur.com/RcgCDrX.png)<br>
     ![Imgur](https://i.imgur.com/aIH3Mkt.png)<br>
2.Run a PostgreSQL server.<br>
3.Pull this repo to `{path_of_XAMPP\htdocs}`<br>
4.Open Command Prompt, Go to `{path_of_XAMPP\htdocs\exam5}`, and run `composer install`<br>
![Imgur](https://i.imgur.com/m1ZFgJf.png)
5.run `php artisan migrate:refresh --seed`<br>
![Imgur](https://i.imgur.com/AUsPbom.png)<br>
6.Go to `http://localhost/exam5/`<br>
# Example a website
![Imgur](https://i.imgur.com/FyVyVZb.png)<br>
![Imgur](https://i.imgur.com/X6Gh6UU.png)<br>
![Imgur](https://i.imgur.com/Yyrs2hN.png)
