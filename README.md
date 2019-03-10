# freeCAD
freeCAD is a Computer Aided Dispatch / Mobile Data Terminal for use in GTA V Role-playing Communities.

# Discord
https://discord.gg/NeRrWZC

# System Requirements
- Operating System
- Linux
- Windows
+ PHP Version
+ Minimum: 5.6
+ Recommended: 7.0 (Or Greater)
- Database
- MySQL
* PDO Must be enabled. (Some hosts require you to request this)

# Support
If you are in-need of support, have a question, need to report an issue, etc, You can join
our Discord: https://discord.gg/NeRrWZC
*We will not provide support for modified files unless you have been given permission.*

# License
freeCAD is released under GNU Affero General Public License.
You can view the license terms and conditions at https://www.gnu.org/licenses/agpl-3.0.en.html
Additionally, You are not allowed to remove the "Powered By freeCAD" branding, any links to Hydrid,
or any credits. 

# Installation
- Download the latest version from GitHub.
- Import 'UPLOAD.SQL' to your database.
- Move the contents from the *Upload* folder, into your website directory.
- Navigate to **includes/connect.php**, and open it with a text-editor.
- Change the database information to yours.
- Go to **www.your-site.com/cad-directory/register.php**
- Create an account
- In your database under `users`, Find the newly created account and set the `usergroup` to **Owner**
- Done! You now have full access over your CAD/MDT system.

