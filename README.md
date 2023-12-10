To Do

Improve & Personalize CSS <br>
  - Improve user display on blogs; <br>
  - Personalize navigation display; <br>
  - Establish title/logo; <br>
  - Correct display for smaller screens on admin, <br>
<br>
Improve comment count method <br>
 - Current method pulls a lot of processing power;<br>
   - CMS executes function, that selects and updates the comment count on each post, each time a comment is called;<br>
   - Value is constantly updated, does the job very well but would crash if it had to this with 1k comments in the system;<br>
 - Create function that updates the comment count each time a comment is created, edited or deleted.<br>
   - Minimizes amount of function occurences when using the site and reduces processing power.<br>
<br>
Refactor functions, utilize more higher functions.<br>
  - DRY is well done for front-end aspects such as index.html, nav and sidebar. Most code is used once and rarely needed twice.<br>
  - Back-end is very repeated. Function files show lots of repeated code where "lower-functions" could implement better OOP principles. <br>
    - Statements can be recreated via functions where array is used to state off individual elements to be inserted/update/added in the database. <br>
    - Query creation could be automated with prepared statements to minimize repetitive code of 3-5 lines per function. <br>
    - Prepended statements would reduce processing power and increase security of application. <br>
<br>
Increase validation across CMS.<br>
  - Inputted information still doesn't receive much validation outside of functional validation (quote escaping, trimming, specialchars); <br>
  - Validation of files received and creation of seperate folders for different images received. <br>
  - Avoid images not uploading or wrong image showing due to image-name already existing in img folder. <br>
  - Make sure author-names/usernames are not repeated as to not confuse users or make possible imitation of another. (Make usernames unique) <br>
  - Emails must be validated and verified before user can go live with account as to avoid bot accounts. <br>
  - Implement Captcha into user and comment creation as to avoid Spam. <br> 
  - Create method to recognize when user has pre-existing account. <br>
  - Make site display dynamic in accordance with whether user is a visitor/admin/common-user. <br>
<br>
Implement Classes and Objects where able<br>
<br>

