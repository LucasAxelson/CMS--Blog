To Do

Improve & Personalize CSS <br>
  - Personalize navigation display; <br>
  - Establish logo; <br>
  - Correct display for smaller screens on admin, <br>
<br>
Improve comment count method <br>
 - Current method pulls a lot of processing power;<br>
   - CMS executes function, that selects and updates the comment count on each post, each time a comment is called;<br>
   - Value is constantly updated, does the job very well but would crash if it had to this with 1k comments in the system;<br>
 - Create function that updates the comment count each time a comment is created, edited or deleted.<br>
   - Minimizes amount of function occurences when using the site and reduces processing power.<br>
<br>
Improve features in project <br>
 - User create a comment (No more comment for for members) + User create a post. <br>
 - Visitors can create a post, requires approval from admin. <br>
 - Redirection to a preview of the need-to-be-approve post where the admin can approve it there. Facilitates judgment of post. <br>
<br>
Increase validation across CMS.<br>
  - Inputted information still doesn't receive much validation outside of functional validation (quote escaping, trimming, specialchars); <br>
  - Validation of files received and creation of seperate folders for different images received. <br>
  - Avoid images not uploading or wrong image showing due to image-name already existing in img folder. <br>
  - Make sure author-names/usernames are not repeated as to not confuse users or make possible imitation of another. (Make usernames unique) <br>
  - Emails must be validated and verified before user can go live with account as to avoid bot accounts. <br>
  - Implement Captcha into user and comment creation as to avoid Spam. <br> 
<br>
Implement Classes and Objects where able<br>

