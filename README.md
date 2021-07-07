# Warzone 1304
This is an old and early project of mine, so the code is not mean to be sane or maintainable. Warzone 1304 was made in 2018. 

I would not recommend using this project for learning purposes or anything else similar (The code is quite bad and most things are hard-coded in).

**Description pulled from site:**
> Warzone 1304 is a browser based free to play multi-player game where you can create your own stronghold to rule. You get to decide what happens in your stronghold and interact with other players through trading and war. You can play with anyone, friend or foe, on Warzone 1304 you can make new friends or foes. In Warzone 1304 you can do anything your heart desires, you can be prosperous through war, trade or diplomacy. You can create treaties for the benefit of doubt. Here you are the commander.

## Dependencies (already included)
* Bootstrap (not stored locally)
* JQuery (not stored locally)
* MySQL

## Contributing
Currently no contributions are allowed to be made

## Installation
Execute the following command: `git clone https://github.com/Paughton/Warzone1304.git`

And import the database: `warzone1304.sql`

Once you have done that navigate to the `system/config.php` file and change all credentials there to fit your needs.

You also want to edit the following line in `register.php`: `mail("YOUREMAILHERE@message.com", "New User!", $message2);` and `$message = "Please copy the link below to confirm your email.\r\nWEBSITEADDRESS/confirm?key=" . $key;`