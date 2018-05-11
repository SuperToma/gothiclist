### Install

 * git clone git@github.com:SuperToma/gothiclist.git
 * cd gothiclist
 * php composer.phar install
 * php bin/console server:run
 
### OK : Controller's action without arguments : 

 * http://localhost:8000/

### KO : Service wired in controller's action

 * http://localhost:8000/autocomplete/artist_group/test
 (route defined in /confg/routes/autocomplete.yaml)