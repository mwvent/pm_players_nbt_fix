# pm_players_nbt_fix
Temporary band aid fix for corrupt player positions in Pocketmine Player NBT files after 0.12 update

Installation:
	git-clone --recursive https://github.com/mwvent/pm_players_nbt_fix.git
	You may need the php5 gmp extension in addition to php5-cli, on ubuntu use sudo apt-get install php5-gmp

usage: php pm_players_nbt_fix <path to players folder in pocketmine directory>

CREATE A BACKUP OF YOUR players FOLDER FIRST - NO DATA IS BACKED UP BEFORE ATTEMPTING REPAIRS
You should probably run this script while the server is offline too - the players with the issue shouldnt be logged in anyway but I have no idea if this will screw with any caches of the player dats Pocketmine may keep?

What this script does
---------------------
Uses PHP-NBT-Decoder-Encoder library to load each player NBT file and read co-ordinates. Checks co-ordinates are valid numerical digits and not scientific notation which appears to trigger the login issue some servers are currentley struggling with. If any bad characters are found they are removed and the NBT file is re-saved.

Example bad co-ordinates
130.01599121094,4.7683714754498E-9,41.578350067139

