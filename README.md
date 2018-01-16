# inventory
Modest inventory system
The plan for this repository is to present the HTML/PHP pages used to fashion a simple inventory system for a school district's technology equipment. It is intended as a low-key aid for use by IT support personnel who have some familiarity with the various technologies: PHP, MySQL, HTML. It does not pretend to be more than that, certainly not an enterprise-level solution.

Inventory management involves different sorts of items (computers, printers, projectors), different attributes of those items (model numbers, item types, i.e. laserjet printer, inkjet printer, etc.), locations (buildings, rooms inside buildings), and other features. Of course, that means bringing in a backend database to hold all this information.

So, while the Web pages included here create a site that works well and has proven very useful, the code requires a backend MySQL database to hold the information referenced or manipulated by the many PHP queries. The database layout is described in the Wiki. The pages for managing computers (and computer-like devices), projectors and smartboards are complete and ready to use. The printer-specific pages need a bit of updating first and will be posted later (it is January 2018 right now). Otherwise everything is pretty much ready to use.
