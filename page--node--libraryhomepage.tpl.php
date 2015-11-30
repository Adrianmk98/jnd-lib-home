<?php 
/**
 * @file			page--node--library.php
 * @summary			Library Frontpage TPL for Laurentian.ca
 * @description		TPL file for (1) library page on LUL TB Theme
 * @author			MB
 * @notes           Removed all Sidebars for this TPL, copy from page.tpl.php if need to be reintroduced
 */
 
 // TODO:  Add SEO Tags
 // TODO:  Testing different scenerios with window.open instead of moving away from laurentian.ca ... 
global $language;
$LANG = $language->language;

if($LANG == 'fr')
{
    setlocale(LC_ALL,'fr_FR');
    $d = strftime('%A, %e %B',time());
    $d = ucfirst($d);
}
else
	$d = date('l, F j');    


// Set appropriate FR title
if($LANG == 'fr') 
	drupal_set_title("Bibliothèque et archives"); 

$node = menu_get_object();
$LANG = $language->language; 

// start with this week (Canada Sunday is first day of the week)
$time = strtotime('sunday last week');
$sunday = $time;
$monday = strtotime('+ 1 day',$time);
$tuesday = strtotime('+ 2 days',$time);
$wednesday = strtotime('+ 3 days',$time);
$thursday = strtotime('+ 4 days',$time);
$friday = strtotime('+ 5 days',$time);
$saturday = strtotime('+ 6 days',$time);

drupal_add_html_head_link(array('rel' => 'stylesheet', 'href' => path_to_theme() . '/css/pagespecific/library.css', 'type' => 'text/css'));
?>
<?php include( path_to_theme() . "/templates/includes/header.inc.php"); ?>



<div class="main-container2 container" vocab="http://schema.org/" typeof="Library">
	<?php if (!empty($page['highlighted'])): ?>
    <div class="highlighted hero-unit"><?php print render($page['highlighted']); ?></div>
    <?php endif; ?>
    
    <?php print render($title_prefix); ?>
    <?php if (!empty($title)): ?>
    <h1 class="page-header <?php if(!empty($node->field_subtitle)) echo "page-header-with-subtitle"; ?>"><?php print $title; ?></h1>
    <?php endif; ?>
    
    
    <?php if(!empty($node->field_subtitle)): ?>
       <h4 class="subtitle"><?php print $node->field_subtitle[$node->language][0]['value']; ?></h4>
    <?php endif; ?>
    
    <?php print render($title_suffix); ?> <?php print $messages; ?>
    <?php if (!empty($tabs)): ?>
    <?php print render($tabs); ?>
    <?php endif; ?>
    <?php if (!empty($page['help'])): ?>
    <div class="well"><?php print render($page['help']); ?></div>
    <?php endif; ?>
    <?php if (!empty($action_links)): ?>
    <ul class="action-links">
    <?php print render($action_links); ?>
    </ul>
    <?php endif; ?>
    <div class="row-fluid heightfix lulContent padt20 padb20">
      <div class="span9">
        <h1 class="page-header2" property="name"><?php echo $LANG == 'en' ? 'J.N. Desmarais Library &amp; Archives':'Bibliothèque et archives <span class="desktoponly">J. N. Desmarais</span>'; ?></h1>
        <ul id="librarySearch" class="nav nav-tabs nav-append-content">
          <li class="active"><a href="#catalogue" data-toggle="tab" id="librarySearchTab" class="dropdown-toggle">Catalogue&nbsp;&nbsp;&nbsp;<b class="caret"></b></a> 
          	<ul class="dropdown-menu">
                <li class="current filtermenu"><a data-filter="keyword" href="#catalogue" ><?php echo $LANG == 'en' ? 'By Keyword' : 'Par mot-clé'; ?></a></li>
                <li class="filtermenu"><a data-filter="title" href="#catalogue" ><?php echo $LANG == 'en' ? 'By Title' : 'Par titre'; ?></a></li>
                <li class="filtermenu"><a data-filter="jtitle" href="#catalogue" ><?php echo $LANG == 'en' ? 'By Journal Title': 'Par titre de revue'; ?></a></li>
                <li class="filtermenu"><a data-filter="author" href="#catalogue" ><?php echo $LANG == 'en' ? 'By Author' : 'Par auteur'; ?></a></li>
                <li class="filtermenu"><a data-filter="subject" href="#catalogue" ><?php echo $LANG == 'en' ? 'By Subject' : 'Par sujet'; ?></a></li>
                <li class="filtermenu"><a data-filter="series" href="#catalogue" ><?php echo $LANG == 'en' ? 'By Series' : 'Par séries'; ?></a></li>
            </ul>
    	  </li>
          <li><a href="#journals" data-toggle="tab"><?php echo $LANG == 'en' ? 'E-Journals' : 'Revues électroniques'; ?></a></li>
          <li><a href="#databases" data-toggle="tab"><?php echo $LANG == 'en' ? 'Research Databases' : 'Bases de données'; ?></a></li>
          <li><a href="#researchguides" data-toggle="tab"><?php echo $LANG == 'en' ? 'Research Guides' : 'Guides de recherche'; ?></a></li>
        </ul>
        <div id="librarySearchContent" class="tab-content border pad10">
          <div class="tab-pane fade in active" id="catalogue">
            <form action="https://<?php echo $LANG == 'en' ? 'laurentian' : 'laurentienne'; ?>.concat.ca/eg/opac/results" method="get" id="concat">
              <div class="control-group">
                <div class="input-append">
                  <input class="searchbox" id="query" type="text" placeholder="<?php echo $LANG == 'en' ? 'Search by keyword ...' : 'Rechercher par mot-clé ...'; ?>" name="query" />
                  <button class="btn" type="button" id="searchCatalogue"><span class="fui-search"></span></button>
                </div>
              </div>
              <input type="hidden" name="locg" value="103" />
              <input id="detail" type="hidden" name="detail_record_view" value="1" />
            </form>
            <div class="searchoptions"> <a href="https://<?php echo $LANG == 'en' ? 'laurentian' : 'laurentienne'; ?>.concat.ca/eg/opac/advanced?locg=105"><?php echo $LANG == 'en' ? 'Advanced Search' : 'Recherche avancée'; ?></a> | <a href="https://<?php echo $LANG == 'en' ? 'laurentian' : 'laurentienne'; ?>.concat.ca/eg/opac/advanced?pane=numeric"><?php echo $LANG == 'en' ? 'Numeric Search' : 'Recherche numérique'; ?></a><!-- | <a href="https://laurentian.concat.ca/eg/opac/advanced?pane=expert"><?php echo $LANG == 'en' ? 'Expert Search' : 'Recherche experte'; ?></a> --> </div>
          </div>
          <div class="tab-pane fade" id="journals">
          
            <form name="az_user_form" method="post" accept-charset="UTF-8" action="http://sfx.scholarsportal.info/laurentian/az<?php if($LANG == 'fr') echo '?&lang=fre'; ?>" id="scholarportal">
            
                <input type="hidden" name="param_sid_save" value="01db6cbfdb33135b9b43356830c46a89">
                <input type="hidden" name="param_letter_group_script_save" value="">
                <input type="hidden" name="param_current_view_save" value="table">
                <input type="hidden" name="param_textSearchType_save" value="contains">
                <input type="hidden" name="param_lang_save" value="<?php print $LANG == 'en' ? 'eng' : 'fre'; ?>">
                <input type="hidden" name="param_chinese_checkbox_type_save" value="">
                <input type="hidden" name="param_perform_save" value="searchTitle">
                <input type="hidden" name="param_letter_group_save" value="">
                <input type="hidden" name="param_chinese_checkbox_save" value="">
                <input type="hidden" name="param_services2filter_save" value="getFullTxt">
                <input type="hidden" name="param_pattern_save" value="test">
                <input type="hidden" name="param_starts_with_browse_save" value="0">
                <input type="hidden" name="param_jumpToPage_save" value="">
                <input type="hidden" name="param_type_save" value="textSearch">
                <input type="hidden" name="param_langcode_save" value="<?php echo $LANG; ?>">
                
                <input type="hidden" name="param_type_value" value="textSearch">
                <input type="hidden" name="param_jumpToPage_value" value="">
                
                 <div class="control-group">
                    <div class="input-append">
                        <input class="searchbox" id="param_pattern_value" type="text" placeholder="<?php echo $LANG == 'en' ? 'Search for journals ...' : 'Rechercher des revues ...'; ?>" name="param_pattern_value" />
                        
                        <button class="btn" type="button" id="searchJournals"><span class="fui-search"></span></button>
                    </div>
                  </div>
                
                
                
              <input type="radio" name="param_textSearchType_value" id="startsWith" value="startsWith" style="display:none;">
               
                <input type="radio" name="param_textSearchType_value" id="contains" value="contains" checked="checked" style="display:none;">
                
                
                <!-- UI Script Control -->
                <input type="hidden" name="param_starts_with_browse_value" value="0">
                
                <!-- for ajax -->
                <input type="hidden" name="param_ui_control_scripts_value" value="">
                
                
                <!-- needed to be passed as one of ajax parameters-->
                <input type="hidden" name="param_chinese_checkbox_value" id="param_chinese_checkbox_value" value="">
            
            </form>
             <div class="searchoptions"> <a href="http://sfx.scholarsportal.info/laurentian/az?param_sid_save=01db6cbfdb33135b9b43356830c46a89&param_letter_group_script_save=&param_current_view_save=table&param_textSearchType_save=contains&param_lang_save=eng&param_chinese_checkbox_type_save=&param_perform_save=locate&param_letter_group_save=&param_chinese_checkbox_save=&param_services2filter_save=getFullTxt&param_pattern_save=testsdfsd&param_starts_with_browse_save=0&param_jumpToPage_save=&param_type_save=textSearch&param_langcode_save=en&param_jumpToPage_value=&param_pattern_value=&param_textSearchType_value=contains&param_issn_value=&param_vendor_active=1&param_locate_category_active=1"><?php echo $LANG == 'en' ? 'Advanced Search' : 'Recherche avancée'; ?></a> </div>
          
          </div>
          <div class="tab-pane fade" id="databases">
          	 <form>
               <select class="searchbox2" id="dblist">
               	<option id="blank" data-url="#" selected="selected"><?php echo $LANG == 'en' ? 'Please make a selection' : 'Veuillez faire une sélection'; ?> ...</option>
               <?php
			   	// Using JSON Feed from Biblio Service
					$DBs = $LANG == 'en' ? 'http://laurentian.ca/feeds/library-dbEN.json' : 'http://laurentian.ca/feeds/library-dbFR.json'; 
					$json = json_decode(file_get_contents($DBs),true);
					
					foreach($json as $key => $value){	
						$db = key($value);  
						// Replace ' with ’, so it doesn't break <option> tag
						$desc = str_replace("'","’",$value[$db]['description']);
						print "<option data-url='".$value[$db]['url']. "'  data-desc='".$desc."'>".$db."</option>"; 
					}
                ?>		   
               </select>
               <button class="btn" type="button" id="searchDB"><span class="fui-search"></span></button>
           </form>
           <div class="searchoptions padt10"> <a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>databases-a-z"><?php echo $LANG == 'en' ? 'View All' : 'Afficher tous'; ?></a></div>
           <p class="padt20 pt13 justify" id="dbdesc"></p>
          </div>
          <div class="tab-pane fade" id="researchguides">
           
            <form>
               <select class="searchbox2" id="researchguidelist">
               	<option id="blank" data-url="#"><?php echo $LANG == 'en' ? 'Please make a selection' : 'Veuillez faire une sélection'; ?> ...</option>
                <?php
			   	// Using JSON Feed from Biblio Service
					$guides = $LANG == 'en' ? 'http://laurentian.ca/feeds/library-guidesEN.json' : 'http://laurentian.ca/feeds/library-guidesFR.json'; 
					$json = json_decode(file_get_contents($guides),true);
					
					foreach($json as $key => $value){	
						
							$db = key($value);  
							$descc = $value[$db]['description'];
							
							$biblioURL = $LANG == 'en' ? 'https://biblio.laurentian.ca/research/' : 'https://biblio.laurentian.ca/research/fr/';
							
						if($value[$db]['category'] != "Service" && $value[$db]['category'] != "Archives")
							print "<option data-url='".$biblioURL.$value[$db]['url']. "'  data-category='". $value[$db]['category']."'>".$db."</option>"; 
					}
                ?>		 
               </select>
               <button class="btn" type="button" id="searchGuides"><span class="fui-search"></span></button>
           </form>
           <div class="searchoptions padt10"> <a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>guides"><?php echo $LANG == 'en' ? 'View All' : 'Afficher tous'; ?></a> <?php if($LANG == 'fr') echo " | <a href='http://biblio.laurentian.ca/research/fr/guides/guide-de-ressources-en-fran%C3%A7ais'>Guide des ressources en français</a>"; ?></div>

          
          </div>
        </div> <!-- /.tab-content --> 
        <div id="twitter"><!-- Will be auto-generated by JS --></div>
        <div id="libnews"><?php
        if ($LANG == 'fr') {
            $news_link = "https://biblio.laurentian.ca/research/fr/nouvelles";
            $news_text = "Nouvelles...";
        }
        else {
            $news_text = "More news...";
            $news_link = "https://biblio.laurentian.ca/research/news";
        }
        echo "<span style='float:right'><a href='$news_link'>$news_text</a></span></div>";?>
      </div> <!-- /.span9 -->
      <div class="span3" id="libraryHours">
      	<div class="border">
            <div class="dates">
              <?php if($LANG == 'en') { ?>
                <span id="Sun" data-day="<?php echo date('m/d/Y',$sunday);?>">S</span><span id="Mon" data-day="<?php echo date('m/d/Y',$monday);?>">M</span><span id="Tue" data-day="<?php echo date('m/d/Y',$tuesday);?>">T</span><span id="Wed" data-day="<?php echo date('m/d/Y',$wednesday);?>">W</span><span id="Thu" data-day="<?php echo date('m/d/Y',$thursday);?>">T</span><span id="Fri" data-day="<?php echo date('m/d/Y',$friday);?>">F</span><span id="Sat" data-day="<?php echo date('m/d/Y',$saturday);?>">S</span>
              <?php } else { ?>
       <span id="Sun" data-day="<?php echo date('m/d/Y',$sunday);?>">D</span><span id="Mon" data-day="<?php echo date('m/d/Y',$monday);?>">L</span><span id="Tue" data-day="<?php echo date('m/d/Y',$tuesday);?>">M</span><span id="Wed" data-day="<?php echo date('m/d/Y',$wednesday);?>">M</span><span id="Thu" data-day="<?php echo date('m/d/Y',$thursday);?>">J</span><span id="Fri" data-day="<?php echo date('m/d/Y',$friday);?>">V</span><span id="Sat" data-day="<?php echo date('m/d/Y',$saturday);?>">S</span>
              <?php } ?>
            </div>
            <div class="today arrow">
            	<p class="jnd"></p>
            	<p class="arc"></p>
            	<p class="jwt"></p>
            	<p class="uos"></p>
            </div>
            <div class="moreHours">
				<a href="/<?php echo $LANG == 'en' ? 'library-hours' : 'biblio-horaire'; ?>"><?php echo $LANG == 'en' ? 'View all campus library hours' : 'Voir tous les horaires'; ?> &raquo;</a>
            </div>
        </div>
        <?php /*  RM'd because Archives want two timeslots.
        <div>
            <p class="notice"><?php echo $LANG == 'en' ? 'Archives are CLOSED between 12:00PM and 1:00PM.' : 'Les Archives sont FERMÉ entre 12h et 13h.'; ?></p>
        </div>
		*/
		?>
      </div>
      
    </div> <!--/.row-fluid-->
</div> <!-- /.container -->


<div class="main-container2 libraryMenuShadow container">
  <div class="row-fluid libraryMenu lulContent" id="lulContent">
    <div class="span25">
      <h5><a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>guides/archives">Archives</a></h5>
      <ul>
        <li><a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>guides/archives#tab2"><?php echo $LANG == 'en' ? 'Policies &amp; Procedures' : 'Politiques et procédures'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/archival-fonds' : 'http://biblio.laurentian.ca/research/fr/guides/fonds-darchives'; ?>"><?php echo $LANG == 'en' ? 'Archival Fonds' : 'Fonds d\'archives'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/anglican-diocese-moosonee' : 'https://biblio.laurentian.ca/research/fr/guides/registres-de-l%C3%A9glise-anglicane'; ?>"><?php echo $LANG == 'en' ? 'Anglican Diocese of Moosonee Fonds' : 'Fonds anglican du diocèse de Moosonee'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/special-collections-archives' : 'http://biblio.laurentian.ca/research/fr/guides/collections-sp%C3%A9ciales-archives'; ?>"><?php echo $LANG == 'en' ? 'Special Collections' : 'Collections spéciales'; ?></a></li>        
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/archives#tab3': 'http://biblio.laurentian.ca/research/fr/guides/archives#tab3'; ?>"><?php echo $LANG == 'en' ? 'Related Sites' : 'Sites connexes'; ?></a></li>
      </ul>
    </div>
    <div class="span25">
      <h5><a href="https://<?php echo $LANG == 'en' ? 'laurentian' : 'laurentienne'; ?>.concat.ca/eg/opac/login?redirect_to=/eg/opac/myopac/main?locg=105"><?php echo $LANG == 'en' ? 'myLibrary' : 'maBiblio'; ?></a></h5>
      <ul>
        <li><a href="https://<?php echo $LANG == 'en' ? 'laurentian' : 'laurentienne'; ?>.concat.ca/eg/opac/login?redirect_to=/eg/opac/myopac/main?locg=105"><?php echo $LANG == 'en' ? 'My Account' : 'Mon compte'; ?></a></li>
        <li><a href="http://biblio.laurentian.ca/reserves/<?php if ($LANG == 'fr') echo "#"; ?>"><?php echo $LANG == 'en' ? 'Course Reserves' : 'Réserves pour les cours'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/managing-citations' : 'https://biblio.laurentian.ca/research/fr/guides/gestion-citations'; ?>"><?php echo $LANG == 'en' ? 'Managing citations' : 'Logiciels bibliographiques'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/interlibrary-loans-racer' : 'http://biblio.laurentian.ca/research/fr/guides/pr%C3%AAt-entre-biblioth%C3%A8ques-racer'; ?>"><?php echo $LANG == 'en' ? 'Interlibrary Loan' : 'Prêt entre bibliothèques'; ?></a></li>
      </ul>
    </div>
    <div class="span25">
      <h5><a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>guides"><?php echo $LANG == 'en' ? 'Research' : 'Recherche'; ?></a></h5>
      <ul>
        <li><a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>databases-a-z"><?php echo $LANG == 'en' ? 'Databases' : 'Bases de données'; ?></a></li>
        <li><a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>guides"><?php echo $LANG == 'en' ? 'Subject Guides' : 'Guides par discipline'; ?></a></li>
        <li><a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>services"><?php echo $LANG == 'en' ? 'Research Help' : 'Aide à la recherche'; ?></a></li>
        <li><a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>services/#tab8"><?php echo $LANG == 'en' ? 'Research Skills Tutorial' : 'Tutoriel de compétences de recherche'; ?></a></li>
        <!--<li><a href="https://biblio.laurentian.ca/research/pages/workshops-atelier"><?php echo $LANG == 'en' ? ' In-person Library Workshops' :  'Ateliers offerts à la Bibliothèque'; ?></a></li>-->
      </ul>
    </div>
    <div class="span25">
      <h5><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/content/resources' : 'http://biblio.laurentian.ca/research/fr/content/ressources'; ?>"><?php echo $LANG == 'en' ? 'Resources' : 'Ressources'; ?></a></h5>
      <ul>
        <li><a href="<?php echo $LANG == 'en' ? 'http://laurentian.concat.ca':'http://laurentienne.concat.ca'; ?>">Catalogue</a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/data-and-statistics' : 'http://biblio.laurentian.ca/research/fr/guides/donn%C3%A9es-et-statistiques'; ?>"><?php echo $LANG == 'en' ? 'Data' : 'Données'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/geospatial-data-lu' : 'http://biblio.laurentian.ca/research/fr/guides/donn%C3%A9es-g%C3%A9ospatiales'; ?>" title="<?php echo $LANG == 'en' ? 'Geographic Information Systems' : 'Système d\'information géographique'; ?>"><?php echo $LANG == 'en' ? 'GIS' : 'SIG'; ?></a></li>
        <li><a href="http://zone.biblio.laurentian.ca/dspace/?locale=<?php echo strtolower($LANG); ?>"><?php echo $LANG == 'en' ? 'LU Zone' : 'Zone UL'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/content/previous-exams' : 'http://biblio.laurentian.ca/research/fr/content/examens-des-ann%C3%A9es-pr%C3%A9c%C3%A9dentes'; ?>"><?php echo $LANG == 'en' ? 'Previous Exams' : 'Examens des années précédentes'; ?></a></li>
      </ul>
    </div>
    <div class="span25">
      <h5><a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr"; ?>/service">Services</a></h5>
      <ul>
        <li><a href="http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>services"><?php echo $LANG == 'en' ? 'for Students' : 'pour étudiants'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/services-faculty' : 'http://biblio.laurentian.ca/research/fr/guides/services-de-biblioth%C3%A8que-%C3%A0-l%E2%80%99intention-du-corps-professoral-2013-2014'; ?>"><?php echo $LANG == 'en' ? 'for Faculty' : 'pour le corps professoral'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/services-other-library-users' : 'http://biblio.laurentian.ca/research/fr/guides/services-pour-autres-utilisateurs'; ?>"><?php echo $LANG == 'en' ? 'for Staff' : 'pour les employés'; ?> </a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/services-other-library-users' : 'http://biblio.laurentian.ca/research/fr/guides/services-pour-autres-utilisateurs'; ?>#tab2"><?php echo $LANG == 'en' ? 'for Alumni' : 'pour les anciens'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/guides/services-other-library-users' : 'http://biblio.laurentian.ca/research/fr/guides/services-pour-autres-utilisateurs'; ?>#tab3"><?php echo $LANG == 'en' ? 'for Visitors' : 'pour les visiteurs'; ?></a></li>
      </ul>
    </div>
  </div> <!-- /.row-fluid-->
</div> <!-- /.container-->
<div class="main-container2 container">
  <div class="row-fluid padt20 padb20 quicklinks">
  	<span class="<?php echo $LANG == 'en' ? 'span3' : 'span4'; ?>">
    	<a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/contact-us' : 'http://biblio.laurentian.ca/research/fr/coordonn%C3%A9es-et-renseignements'; ?>"><img src="<?php echo "/" . path_to_theme() .  "/images/library/email.png"; ?>" alt="<?php echo $LANG == 'en' ? 'Contact &amp; About Us' : 'Coordonnées et renseignements'; ?>" /> <?php echo $LANG == 'en' ? 'Contact &amp; About Us' : 'Coordonnées et renseignements'; ?></a>
    </span>
    <span class="<?php echo $LANG == 'en' ? 'span4' : 'span3'; ?>">
    	<a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/content/donations-jn-desmarais-library' : 'http://biblio.laurentian.ca/research/fr/content/dons-%C3%A0-la-biblioth%C3%A8que-jn-desmarais'; ?>"><img src="<?php echo "/" . path_to_theme() .  "/images/library/donate.png"; ?>" alt="Donate" /> <?php echo $LANG == 'en' ? 'Giving to the Library &amp; Archives' : 'Dons à la Bibliothèque'; ?></a>
    </span>
    <span class="span2">
    	<a href="<?php echo $LANG == 'en' ? 'http://biblio.laurentian.ca/research/content/library-accessibility-services' : 'http://biblio.laurentian.ca/research/fr/content/services-daccessibilit%C3%A9'; ?>"><img src="<?php echo  "/" .  path_to_theme() .  "/images/library/access.png"; ?>" alt="Accessibility" /><?php echo $LANG == 'en' ? 'Accessibility' : 'Accessibilité'; ?></a>
    </span>
    <span class="span3">
    	<a href="/<?php echo $LANG == 'en' ? 'library-events' : 'biblio-evenements'; ?>#cal"><img src="<?php echo  "/" . path_to_theme() .  "/images/library/cal.png"; ?>" alt="Library Calendar" /><?php echo $LANG == 'en' ? 'Library Calendar' : 'Événements <span class="hidden">de la Biblio</span>'; ?></a>
    </span>
  </div>
</div>
<div id="lang"  style="display:none;" data-lang="<?php echo $language->language;?>"></div>
<?php include( path_to_theme() . "/templates/includes/footer.inc.php"); ?>
<div id="askalibrarian">
	<?php if($LANG == 'fr') echo "<style>#askalibrarian .toggle h6 { padding-top:4px !important; }</style>"; ?>
	<div class="toggle"><h6><?php echo $LANG == 'en' ? 'Ask A Librarian' : 'Clavardez avec nos bibliothécaires'; ?><i class="icon-chevron-up fr"></i></h6></div>
    <div class="info">
        <iframe src="/askalibrarian.php?lang=<?php echo strtolower($LANG); ?>" width="200" height="170" frameborder="0" scrolling="no"></iframe>
        <a class="moreinfo" href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/contact-us':'https://biblio.laurentian.ca/research/fr/coordonn%C3%A9es-et-renseignements'; ?>" target="_blank"><i class="fa fa-info-circle"></i> <?php echo $LANG == 'en' ? 'More info...' : 'En savoir plus...'; ?></a></p>
    </div>
</div>
<script src="/<?php $FRonly = $LANG == 'fr' ? 'FR' : ''; echo path_to_theme() . "/js/jquery.twitterLibrary" . $FRonly . ".js"; ?>"></script> 
<script>
// Main JS File
// Incl. inline due to needing PHP $language->language
$(document).ready(function(){
	
	$('.toggle').on("click",function(){
		console.log('yes');
		if($('#askalibrarian').hasClass("open")){
			$('.toggle i').removeClass("icon-chevron-down").addClass("icon-chevron-up");
			$('#askalibrarian').removeClass("open").animate({ bottom: -215 });
		} else {			
			$('.toggle i').removeClass("icon-chevron-up").addClass("icon-chevron-down");
			$('#askalibrarian').addClass("open").animate({ bottom: 0 });
		}
	});
	// Twitter Feed
	$("#twitter").tweet({
		username: "LaurentianLib",
		page: 1,
		avatar_size: 74,
		count: 1,
		loading_text: "..."
	}).bind("loaded", function() {
			// prepend @LaurentianLib text before tweet
			$('.tweet_text').prepend("<?php echo $LANG == 'en' ? "<a href='//twitter.com/LaurentianLib' target='_blank'>@LaurentianLib</a>: " : "<a href='//twitter.com/BibLaurentienne' target='_blank'>@BibLaurentienne</a> : "; ?>");
			$(".tweet_text a").attr("target","_blank");
	});
	
	$('#twitter a').click(function(){
		var href = $(this).attr("href");
		if (!(href || href.charAt(0) == '/' || href.charAt(0) == '#' || href.charAt(0) == '')){
		  window.open(this.href);
		  return false;
		}
	});
	
	$('.filtermenu a').on("click",function(e){
		 e.stopPropagation();
		 
		 // remove active classes, add current classes
		 $('.filtermenu').removeClass("current").removeClass("active");
		 $(this).parent().addClass("current");
		 
		 $("#qtype option").attr("selected",""); // reset all selected
		 $("#qtype option[value=" + $(this).data("filter") + "]").attr('selected','selected'); // select filter
		 
		 placeholder = "<?php echo $LANG == 'en' ? 'Search by ': 'Rechercher par '; ?>";
		 filteredby = $(this).data("filter");
		 
		 // i10n 
		 switch (filteredby) {
			case "keyword":
				filteredby = "<?php echo $LANG == 'en' ? 'keyword' : 'mot-clé'; ?> ";
				break;
			case "title":
				filteredby = "<?php echo $LANG == 'en' ? 'title' : 'titre'; ?> ";
				break;
			case "jtitle":
				filteredby = "<?php echo $LANG == 'en' ? 'journal title' : 'titre de revue'; ?> ";
				break;
			case "author":
				filteredby = "<?php echo $LANG == 'en' ? 'author' : 'auteur'; ?> ";
				break;
			case "subject":
				filteredby = "<?php echo $LANG == 'en' ? 'subject' : 'sujet'; ?> ";
				break;
			case "series":
				filteredby = "<?php echo $LANG == 'en' ? 'series' : 'séries'; ?> ";
				break;
		 }
		 $("#query").attr("placeholder", placeholder + filteredby + " ..."); // Update Placeholder as per filter
		 
		 $('#librarySearchTab').click(); // Tab doesn't stay selected with dropdown? 
	});
	
	// Catalogue Search
	$("#searchCatalogue").on("click", function(){
		// TODO: Error Checking
		$('#concat').submit();
	});
	
	// Journals Search
	$("#searchJournals").on("click", function(){
		// TODO: Error Checking
		$('#scholarportal').submit();
	});
	
	
	// Databases Search
	// Redirect to this URL (on "Search" Databases)
	var URLforDB = "http://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>databases-a-z";
	$('#dblist').on("change", function(){
		var optionSelected = $("option:selected",this);
		URLforDB = optionSelected.data("url");
		if( optionSelected.data("desc") != "")
			$('#dbdesc').hide().text( optionSelected.data("desc") ).fadeIn(250);
		else
			$('#dbdesc').fadeOut();
	});
	
	$("#searchDB").on("click",function(){
		window.location = URLforDB;
	});
	
	// Research Guides Search
	$('#researchguidelist').on("change", function(){
		var optionSelected = $("option:selected",this);
		URLforGuides = optionSelected.data("url");
	});
	
	$("#searchGuides").on("click",function(){
		window.location = $('#researchguidelist option:selected').data("url");
	});
	
	// Hours of Operation Widget
	// Set correct date as "active"
	$('#<?php echo date(D);?>').addClass("active"); 
	
	$('#libraryHours .dates span').on("click",function(){
    		
            updateLibraryTime($(this).attr('id'));
	});
	
	
	
    bottom('<?php echo date("m/d/Y");?>','JND');
    $('.jnd').addClass("active");
    updateLibraryTime($('.dates .active').attr('id'));

	$(".jnd, .arc , .jwt, .uos").on("click",function(){
		$(".today .active").removeClass("active");
		$(this).addClass("active");
		
         date = $('.dates span.active').data('day');
         library = $(this).attr('class');
         temp = library.split(' ');
         library = temp[0];
         library = library == 'arc' ? 'Archives' : library.toUpperCase();
    
         bottom(date,library);
   

		
	});
});


function updateLibraryTime(day)
{
            // Move Highlighted Class "active"
            library = ' ';
             $('#libraryHours .dates .active').removeClass("active");
            library = $('#libraryHours .border .today p.active').attr('class');
            console.log('library: ' +library);
            if(library)
           {
                temp = library.split(' ');
                library = temp[0];
                library = library == 'arc' ? 'Archives' : library.toUpperCase();
                bottom($('#'+day).data('day'),library);
            }


        $('#'+day).addClass("active");
        

        var request = $.ajax({
          url: "<?php echo "/" . path_to_theme(). "/templates/includes/feeds/json.librarycal.php"; ?>",
          type: "POST",
          async: false,
          data: { date: $('#'+day).data('day'), lang :"<?php echo $LANG;?>", mode: 'single'},
          dataType: "html"
        }).done(function( msg ) {
    
            var information = jQuery.parseJSON(msg);

            // Based on the date selected, rewrite HTML

            
            $('#libraryHours .jnd').html("<label>J.N.D.</label>"+information.JND);
            $('#libraryHours .arc').html("<label>Archives</label>"+information.Archives);
            $('#libraryHours .jwt').html("<label>Huntington</label>"+information.JWT);
            $('#libraryHours .uos').html("<label>U&nbsp;<?php echo $LANG == 'en' ? 'of' : 'de'; ?>&nbsp;S</label>"+information.UoS);

            
        }); 
}

function bottom(day,library)
{

    var request = $.ajax({
          url: "<?php echo "/" . path_to_theme(). "/templates/includes/feeds/json.librarycal.php"; ?>",
          type: "POST",
          async: false,
          data: { date:  day, lang :"<?php echo $LANG;?>", mode: 'single'},
          dataType: "html"
        }).done(function( msg ) {
    
            var information = jQuery.parseJSON(msg);

            //console.log(information); // debug
			
            // Update HTML based on date selected
            date = day.split('/');
          
           if(library == 'JND' || library == 'jnd')
                open = information.JND.split('-');
            else if(library == 'Archives')
                open = information.Archives.split('-');
            else if(library == 'JWT')
                 open = information.JWT.split('-');
            else if(library == 'UOS')
                 open = information.UoS.split('-');

          
            if(open.length == 2)
				formatdateLang(day,false);
            else
                formatdateLang(day,true);

        });

}
function formatdateLang(today,closed)
{
    var request = $.ajax({
          url: "<?php echo "/" .  path_to_theme(). "/templates/includes/feeds/json.librarycal.php"; ?>",
          type: "POST",
          async: true,
          data: { date:  today, lang :"<?php echo $LANG;?>", format: 'true', open:open[0], close:open[1]},
          dataType: "html"
        }).done(function( msg ) {
    
            var info = jQuery.parseJSON(msg);
                
        });

}
</script>
