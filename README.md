<a href="https://aws.amazon.com/what-is-cloud-computing"><img src="https://d1.awsstatic.com/logos/aws-logo-lockups/poweredbyaws/Powered-By_logo-horiz_RGB.87c70af8a8a34b68606524ade6261db083947a14.png" alt="Powered by AWS Cloud Computing" width="200" style="float:right"></a>
<h3>What Does This Do?</h3>
<p>This module provides no end user functionality.  It is designed to be integrated into other modules to provide AWS SDK API access.  That's it, nothing more.</p>
<h3>How Do I Use It</h3>
<p>This module provides a simple wrapper around all of the methods and variables of AWS PHP SDK. It is designed for use by other module developers.</p>

<h3>File Permissions (Linux/WSL)</h3>
<p>When using the AWS Profile authentication method, the web server (e.g. Apache running as <code>www-data</code>) must be able to read your credentials file. If you get a "Cannot read credentials" error, fix permissions with:</p>
<pre>sudo chmod 644 ~/.aws/credentials</pre>
<p><strong>Note:</strong> This makes the file world-readable. For tighter security on shared servers, use group-based access instead:</p>
<pre>sudo usermod -aG your_username www-data
sudo chmod 640 ~/.aws/credentials
sudo systemctl restart apache2</pre>
<h3>Feedback/Support</h3>
<p>This module does not include commercial support. However, there are a number of resources available to help you with it:</p>
<ul>
<li>For the latest version of this module or to file a Feature Request or Bug Report, please visit the Module Forge
<a href="http://dev.cmsmadesimple.org/projects/AWSSDK" target="_blank">AWSSDK Page</a>.</li>
</ul>
<h3>Interested in development?</h3>
<p>Check out the <a href="https://github.com/magalh/AWSSDK">Github repository</a>.</p>
<h3>Copyright and License</h3>
<p>Copyright &copy; 2023, Magal Hezi <a href="mailto:h_magal@hotmail.com">&lt;h_magal@hotmail.com&gt;</a>. All Rights Are Reserved.</p>
<p>This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.</p>
<p>However, as a special exception to the GPL, this software is distributed
as an addon module to CMS Made Simple.  You may not use this software
in any Non GPL version of CMS Made simple, or in any version of CMS
Made simple that does not indicate clearly and obviously in its admin
section that the site was built with CMS Made simple.</p>
<p>This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
Or read it <a href="http://www.gnu.org/licenses/licenses.html#GPL">online</a></p>

<p><strong>Amazon Web Services, AWS, and the Powered by AWS logo are trademarks of Amazon.com, Inc. or its affiliates</strong></p>
