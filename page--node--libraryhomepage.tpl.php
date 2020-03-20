<?php 
/**
 * @file			page--node--library.php
 * @summary			Library Frontpage TPL for Laurentian.ca
 * @description		TPL file for (1) library page on LUL TB Theme
 * @author			MB
 * @notes           Removed all Sidebars for this TPL, copy from page.tpl.php if need to be reintroduced
 */
 
global $language;
global $base_url;
$LANG = $language->language;

$twitter_widget = '<a class="twitter-timeline" data-width="300" data-height="400" href="https://twitter.com/LaurentianLib?ref_src=twsrc%5Etfw">Tweets by LaurentianLib</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
$d = date('l, F j');    
$lib_title = 'Library and Archives';
$lib_title_schema = 'J.N. Desmarais Library and Archives';
$lib_name_schema = 'Laurentian University Library and Archives';
$lib_ref_schema = 'Reference and research assistance';
$lib_contact_url = 'https://biblio.laurentian.ca/research/contact-us';
$lib_url_en = 'https://www1.laurentian.ca/library';
$lib_url_fr = 'https://www1.laurentienne.ca/bibliotheque';
$lib_url = $lib_url_en;

if($LANG == 'fr')
{
    // Set appropriate FR title
    drupal_set_title("Bibliothèque et archives"); 
    setlocale(LC_ALL,'fr_FR');
    $d = strftime('%A, %e %B',time());
    $d = ucfirst($d);
    $twitter_widget = '<a class="twitter-timeline" data-lang="fr" data-width="300" data-height="400" data-theme="light" href="https://twitter.com/BibLaurentienne?ref_src=twsrc%5Etfw">Tweets by BibLaurentienne</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
    $lib_title = 'Bibliothèque et archives';
    $lib_title_schema = 'Bibliothèque et archives J. N. Desmarais';
    $lib_name_schema = 'Bibliothèque et archives Université Laurentienne';
    $lib_ref_schema = 'Aide dans vos recherches';
    $lib_contact_url = 'https://biblio.laurentian.ca/research/fr/coordonnées-et-renseignements';
    $lib_url = $lib_url_en;
}

$node = menu_get_object();

// start with this week (Canada Sunday is first day of the week)
$time = strtotime('sunday last week');
$sunday = $time;
$monday = strtotime('+ 1 day',$time);
$tuesday = strtotime('+ 2 days',$time);
$wednesday = strtotime('+ 3 days',$time);
$thursday = strtotime('+ 4 days',$time);
$friday = strtotime('+ 5 days',$time);
$saturday = strtotime('+ 6 days',$time);

drupal_add_html_head_link(array('rel' => 'stylesheet', 'href' => '/' . path_to_theme() . '/css/pagespecific/library.css?v=106', 'type' => 'text/css'));

function parse_news_feed($news_atom, $max_items = 4, $max_age = '30 days') {
    $news_items = array();
    $news_feed = implode(file($news_atom));
    $news_xml = simplexml_load_string($news_feed);
    $news_json = json_encode($news_xml);
    $news_array = json_decode($news_json,TRUE);
    // grab at least the first item
    for ($i = 0; $i < $max_items; $i++) {
      $news_item = $news_array['channel']['item'][$i];
      $news_date = date_parse($news_item['pubDate']);
      // give it an ISO publication date
      $news_item['date'] = sprintf("%04d-%02d-%02d", $news_date['year'], $news_date['month'], $news_date['day']);
      $margin = date_sub(date_create('now'), date_interval_create_from_date_string($max_age));
      // only show items from the past 30 days
      if ($i and date_create($news_item['date']) < $margin) {
        break;
      }
      // add it to the array
      $news_items[] = $news_item;
    }
    return($news_items);
}
?>
<?php include( path_to_theme() . "/templates/includes/header.inc.php"); ?>

<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "Library",
    "@id": "<?php echo $lib_url ?>",
    "address": {
        "@type": "PostalAddress",
        "name": "<?php echo $lib_name_schema ?>",
        "streetAddress": "935 Ramsey Lake Road",
        "addressLocality": "Sudbury",
        "addressRegion": "ON",
        "addressCountry": "Canada",
        "postalCode": "P3E 2C6"
    },
    "contactPoint": [
        {
            "@type": "ContactPoint",
            "name": "Circulation",
            "contactType": "customer support",
            "telephone": "+1-705-675-4800",
            "email": "circulation@laurentian.ca",
            "availableLanguage" : ["English", "French"]
        },
        {
            "@type": "ContactPoint",
            "name": "<?php echo $lib_ref_schema ?>",
            "contactType": "customer service",
            "email": "reference@laurentian.ca",
            "url": "<?php echo $lib_contact_url ?>",
            "availableLanguage" : ["English", "French"]
        }
    ],
    "name": "<?php echo $lib_name_schema ?>",
    "alternateName": "<?php echo $lib_title_schema ?>",
    "url": "<?php echo $lib_url ?>"
}    
</script>

<div class="main-container2 container" vocab="http://schema.org/" resource="<?php echo $lib_url ?>" typeof="Library">
    <link property="sameAs" href="<?php echo $LANG == 'en' ? $lib_url_fr : $lib_url_en; ?>" />
    <link property="sameAs" href="https://laurentian.concat.ca/eg/opac/library/OSUL" />
    <link property="sameAs" href="https://laurentienne.concat.ca/eg/opac/library/OSUL" />
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



    <div class="row-fluid heightfix lulContent padt20 padb20" id="search-hours">
      <div class="span9">
        <h1 class="page-header2" property="name"><?php echo $lib_title ?></h1>
        <h2><a href="<?php
          echo $LANG == 'en' ? "https://biblio.laurentian.ca/research/content/covid-19-library-and-archives-faq" : "https://biblio.laurentian.ca/research/fr/content/covid-19-foire-aux-questions-biblioth%C3%A8que-et-archives"; ?>"
            style="display: block; padding: 1em; border: 1px solid #a30046; color: #a30046; background-color:#eac7cb; font-weight: bold;"><?php
            echo $LANG == 'en' ? "COVID-19: Library and Archives FAQ" : "COVID-19: Foire aux questions, Bibliothèque et Archives"
        ?></a></h2>
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
          <li><a href="#googlescholar" data-toggle="tab"><?php echo $LANG == 'en' ? 'Scholarly Articles' : 'Articles scientifiques'; ?></a></li>
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
              <input type="hidden" name="locg" value="105" />
              <input id="catalogueSearchType" type="hidden" name="qtype" value="keyword" />
              <input id="detail" type="hidden" name="detail_record_view" value="1" />
            </form>
            <div class="searchoptions"> <a href="https://<?php echo $LANG == 'en' ? 'laurentian' : 'laurentienne'; ?>.concat.ca/eg/opac/advanced?locg=105"><?php echo $LANG == 'en' ? 'Advanced Search' : 'Recherche avancée'; ?></a> | <a href="https://<?php echo $LANG == 'en' ? 'laurentian' : 'laurentienne'; ?>.concat.ca/eg/opac/advanced?pane=numeric"><?php echo $LANG == 'en' ? 'Numeric Search' : 'Recherche numérique'; ?></a><!-- | <a href="https://laurentian.concat.ca/eg/opac/advanced?pane=expert"><?php echo $LANG == 'en' ? 'Expert Search' : 'Recherche experte'; ?></a> --> </div>
          </div>
          <div class="tab-pane fade" id="googlescholar">
            <form name="googlescholar" method="get" accept-charset="utf-8" action="https://scholar.google.ca/scholar" id="gscholar">
                <input type="hidden" name="hl" value="<?php echo($LANG) ?>">
                <input type="hidden" name="inst" value="16499083251021320657">
                 <div class="control-group">
                    <div class="input-append">
                        <input class="searchbox" id="scholar_value" type="text" placeholder="<?php echo $LANG == 'en' ? 'Search for articles in Google Scholar...' : 'Rechercher des articles en Google Scholar...'; ?>" name="q" />
                        <button class="btn" type="button" id="searchArticles"><span class="fui-search"></span></button>
                    </div>
                  </div>
            </form>
          </div>
          <div class="tab-pane fade" id="journals">
          
            <form name="az_user_form" method="post" accept-charset="UTF-8" action="https://sfx.scholarsportal.info/laurentian/az<?php if($LANG == 'fr') echo '?&lang=fre'; ?>" id="scholarportal">
            
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
             <div class="searchoptions"> <a href="https://sfx.scholarsportal.info/laurentian/az?param_sid_save=01db6cbfdb33135b9b43356830c46a89&param_letter_group_script_save=&param_current_view_save=table&param_textSearchType_save=contains&param_lang_save=eng&param_chinese_checkbox_type_save=&param_perform_save=locate&param_letter_group_save=&param_chinese_checkbox_save=&param_services2filter_save=getFullTxt&param_pattern_save=testsdfsd&param_starts_with_browse_save=0&param_jumpToPage_save=&param_type_save=textSearch&param_langcode_save=en&param_jumpToPage_value=&param_pattern_value=&param_textSearchType_value=contains&param_issn_value=&param_vendor_active=1&param_locate_category_active=1"><?php echo $LANG == 'en' ? 'Advanced Search' : 'Recherche avancée'; ?></a> </div>
          
          </div>
          <div class="tab-pane fade" id="databases">
          	 <form>
               <select class="searchbox2" id="dblist">
               	<option id="blank" data-url="#" selected="selected"><?php echo $LANG == 'en' ? 'Please make a selection' : 'Veuillez faire une sélection'; ?> ...</option>
               <?php
			   	// Using JSON Feed from Biblio Service
					$DBs = $LANG == 'en' ? $base_url.'/feeds/library-dbEN.json' : $base_url.'/feeds/library-dbFR.json';

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
           <div class="searchoptions padt10"> <a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>databases-a-z"><?php echo $LANG == 'en' ? 'View All' : 'Afficher tous'; ?></a></div>
           <p class="padt20 pt13 justify" id="dbdesc"></p>
          </div>
          <div class="tab-pane fade" id="researchguides">
           
            <form>
               <select class="searchbox2" id="researchguidelist">
               	<option id="blank" data-url="#"><?php echo $LANG == 'en' ? 'Please make a selection' : 'Veuillez faire une sélection'; ?> ...</option>
                <?php
			   	// Using JSON Feed from Biblio Service
					$guides = $LANG == 'en' ? $base_url.'/feeds/library-guidesEN.json' : $base_url.'/feeds/library-guidesFR.json';
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
           <div class="searchoptions padt10"> <a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>guides"><?php echo $LANG == 'en' ? 'View All' : 'Afficher tous'; ?></a> <?php if($LANG == 'fr') echo " | <a href='https://biblio.laurentian.ca/research/fr/guides/guide-de-ressources-en-fran%C3%A7ais'>Guide des ressources en français</a>"; ?></div>

          
          </div>
        </div> <!-- /.tab-content --> 
      </div><?php /* span9 */ ?>
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
				<a href="/<?php echo $LANG == 'en' ? 'library-hours' : 'biblio-horaire'; ?>"><?php echo $LANG == 'en' ? 'All campus library hours' : 'Tous les horaires'; ?></a>
            </div>
        </div>
    </div>
  </div><?php /* padt20 */ ?>
  <div class="row-fluid heightfix lulContent padb20">
<div class="span9">
  <div class="row-fluid libraryMenu lulContent padb20" id="lulContent">
    <div class="span25">
      <h5 id="archives-links"><a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>guides/archives">Archives</a></h5>
      <ul>
        <li><a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>guides/archives#tab2"><?php echo $LANG == 'en' ? 'Policies &amp; Procedures' : 'Politiques et procédures'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/archival-fonds' : 'https://biblio.laurentian.ca/research/fr/guides/fonds-darchives'; ?>"><?php echo $LANG == 'en' ? 'Archival Fonds' : 'Fonds d\'archives'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/anglican-diocese-moosonee' : 'https://biblio.laurentian.ca/research/fr/guides/registres-de-l%C3%A9glise-anglicane'; ?>"><?php echo $LANG == 'en' ? 'Anglican Diocese of Moosonee Fonds' : 'Fonds anglican du diocèse de Moosonee'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/special-collections-archives' : 'https://biblio.laurentian.ca/research/fr/guides/collections-sp%C3%A9ciales-archives'; ?>"><?php echo $LANG == 'en' ? 'Special Collections' : 'Collections spéciales'; ?></a></li>        
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/archives#tab3': 'https://biblio.laurentian.ca/research/fr/guides/archives#tab3'; ?>"><?php echo $LANG == 'en' ? 'Related Sites' : 'Sites connexes'; ?></a></li>
      </ul>
    </div>
    <div class="span25">
      <h5><a href="https://<?php echo $LANG == 'en' ? 'laurentian' : 'laurentienne'; ?>.concat.ca/eg/opac/login?redirect_to=/eg/opac/myopac/main?locg=105"><?php echo $LANG == 'en' ? 'myLibrary' : 'maBiblio'; ?></a></h5>
      <ul>
        <li><a href="https://<?php echo $LANG == 'en' ? 'laurentian' : 'laurentienne'; ?>.concat.ca/eg/opac/login?redirect_to=/eg/opac/myopac/main?locg=105"><?php echo $LANG == 'en' ? 'My Account' : 'Mon compte'; ?></a></li>
        <li><a href="https://biblio.laurentian.ca/reserves/<?php if ($LANG == 'fr') echo "#"; ?>"><?php echo $LANG == 'en' ? 'Course Reserves' : 'Réserves pour les cours'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/managing-citations' : 'https://biblio.laurentian.ca/research/fr/guides/gestion-citations'; ?>"><?php echo $LANG == 'en' ? 'Managing citations' : 'Logiciels bibliographiques'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/interlibrary-loans-racer' : 'https://biblio.laurentian.ca/research/fr/guides/pr%C3%AAt-entre-biblioth%C3%A8ques-racer'; ?>"><?php echo $LANG == 'en' ? 'Interlibrary Loan' : 'Prêt entre bibliothèques'; ?></a></li>
      </ul>
    </div>
    <div class="span25">
      <h5><a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>guides"><?php echo $LANG == 'en' ? 'Research' : 'Recherche'; ?></a></h5>
      <ul>
        <li><a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>databases-a-z"><?php echo $LANG == 'en' ? 'Databases' : 'Bases de données'; ?></a></li>
        <li><a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>guides"><?php echo $LANG == 'en' ? 'Research Guides' : 'Guides de recherche'; ?></a></li>
        <li><a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>services"><?php echo $LANG == 'en' ? 'Research Help' : 'Aide à la recherche'; ?></a></li>
        <li><a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>services/#tab8"><?php echo $LANG == 'en' ? 'Research Skills Tutorial' : 'Tutoriel de compétences de recherche'; ?></a></li>
        <!--<li><a href="https://biblio.laurentian.ca/research/pages/workshops-atelier"><?php echo $LANG == 'en' ? ' In-person Library Workshops' :  'Ateliers offerts à la Bibliothèque'; ?></a></li>-->
      </ul>
    </div>
    <div class="span25">
      <h5><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/resources' : 'https://biblio.laurentian.ca/research/fr/content/resources'; ?>"><?php echo $LANG == 'en' ? 'Resources' : 'Ressources'; ?></a></h5>
      <ul>
        <li><a href="<?php echo $LANG == 'en' ? 'https://laurentian.concat.ca':'https://laurentienne.concat.ca'; ?>">Catalogue</a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/copyright-lu' : 'https://biblio.laurentian.ca/research/fr/guides/droit-d%E2%80%99auteur-ul'; ?>"><?php echo $LANG == 'en' ? 'Copyright @ LU' : 'Droit d’auteur @ UL'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/data-and-statistics' : 'https://biblio.laurentian.ca/research/fr/guides/donn%C3%A9es-et-statistiques'; ?>"><?php echo $LANG == 'en' ? 'Data' : 'Données'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/geospatial-data-lu' : 'https://biblio.laurentian.ca/research/fr/guides/donn%C3%A9es-g%C3%A9ospatiales'; ?>" title="<?php echo $LANG == 'en' ? 'Geographic Information Systems' : 'Système d\'information géographique'; ?>"><?php echo $LANG == 'en' ? 'GIS' : 'SIG'; ?></a></li>
        <li><a href="https://zone.biblio.laurentian.ca/dspace/?locale=<?php echo strtolower($LANG); ?>"><?php echo $LANG == 'en' ? 'LU|Zone|UL: research repository' : 'LU|Zone|UL : dépôt de recherche'; ?></a></li>
      </ul>
    </div>
    <div class="span25">
      <h5><a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr"; ?>/service">Services</a></h5>
      <ul>
        <li><a href="https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>services"><?php echo $LANG == 'en' ? 'for Students' : 'pour étudiants'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/services-faculty' : 'https://biblio.laurentian.ca/research/fr/guides/services-de-biblioth%C3%A8que-%C3%A0-l%E2%80%99intention-du-corps-professoral-2013-2014'; ?>"><?php echo $LANG == 'en' ? 'for Faculty' : 'pour le corps professoral'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/off-campus-library-services' : 'https://biblio.laurentian.ca/research/fr/guides/services-de-la-biblioth%C3%A8que-hors-campus'; ?>"><?php echo $LANG == 'en' ? 'for Off Campus' : 'pour hors campus'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/services-other-library-users' : 'https://biblio.laurentian.ca/research/fr/guides/services-pour-autres-utilisateurs'; ?>"><?php echo $LANG == 'en' ? 'for Staff' : 'pour les employés'; ?> </a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/services-other-library-users' : 'https://biblio.laurentian.ca/research/fr/guides/services-pour-autres-utilisateurs'; ?>#tab2"><?php echo $LANG == 'en' ? 'for Alumni' : 'pour les anciens'; ?></a></li>
        <li><a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/guides/services-other-library-users' : 'https://biblio.laurentian.ca/research/fr/guides/services-pour-autres-utilisateurs'; ?>#tab3"><?php echo $LANG == 'en' ? 'for Visitors' : 'pour les visiteurs'; ?></a></li>
      </ul>
    </div>
  </div> <!-- /.row-fluid-->
  <div class="row-fluid quicklinks" style="padding-top: 1em">
    <span class="span4">
      <a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/contact-us' : 'https://biblio.laurentian.ca/research/fr/coordonn%C3%A9es-et-renseignements'; ?>"><img src="<?php echo "/" . path_to_theme() .  "/images/library/email.png"; ?>" alt="" /> <?php echo $LANG == 'en' ? 'Contact &amp; About Us' : 'Coordonnées et renseignements'; ?></a>
    </span>
    <span class="span4">
      <a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/contact-us#tab3' : 'https://biblio.laurentian.ca/research/fr/coordonn%C3%A9es-et-renseignements#tab3'; ?>"><img src="<?php echo "/" . path_to_theme() .  "/images/library/donate.png"; ?>" alt="" /> <?php echo $LANG == 'en' ? 'Giving to the Library' : 'Dons à la Bibliothèque'; ?></a>
    </span>
    <span class="span3">
      <a href="<?php echo $LANG == 'en' ? 'https://biblio.laurentian.ca/research/content/library-accessibility-services' : 'https://biblio.laurentian.ca/research/fr/content/services-daccessibilit%C3%A9'; ?>"><img src="https://biblio.laurentian.ca/research/sites/default/files/pictures/access_icon.png" alt="" /><?php echo $LANG == 'en' ? 'Accessibility' : 'Accessibilité'; ?></a>
    </span>
  </div>
  <div style="padding: 1em; margin-left: 1em; margin-right: 1em;"><?php echo
    $LANG == 'en' ? 'We would like to acknowledge that the J.N. Desmarais Library &amp; Archives are located on the traditional territory of the Atikameksheng Anishnaabek, which is within the Robinson-Huron Treaty territory.'
    : 'Nous désirons reconnaître que la Bibliothèque J.N. Desmarais &amp; les archives sont situés sur le territoire traditionnel de la Nation Atikameksheng Anishnawbek, qui fait partie du territoire désigné dans le traité Robinson-Huron.'; ?>
  </div>

  </div><?php /* span9 */ ?>
      <div class="span3 news-panel">
        <div class='needs-js'><?php echo $LANG == 'en' ? 'Chat loading...' : 'Clavardez...'; ?></div>
        <div id="libnews"><?php
            if ($LANG == 'fr') {
                $news_atom = 'https://biblio.laurentian.ca/research/fr/news.xml';
                $news_link = "https://biblio.laurentian.ca/research/fr/nouvelles";
                $news_heading = "Nouvelles";
            }
            else {
                $news_atom = 'https://biblio.laurentian.ca/research/news.xml';
                $news_link = "https://biblio.laurentian.ca/research/news";
                $news_heading = "News";
            }

            $news_items = parse_news_feed($news_atom);
            print("<h5><a href='$news_link'>$news_heading</a></h5><ul>");
            foreach ($news_items as $news_item) {
              print("<li><a href='$news_item[link]'>$news_item[title]</a> ($news_item[date])</li>\n");
            }
        ?></div>
        <div id="twitter-widget"><?php echo($twitter_widget); ?></div>
      </div>
    </div> <!--/.row-fluid-->
  </div> <!-- /.container -->
</div>
<div id="lang"  style="display:none;" data-lang="<?php echo $language->language;?>"></div>
<?php include( path_to_theme() . "/templates/includes/footer.inc.php"); ?>
<script>
// Main JS File
// Incl. inline due to needing PHP $language->language
$(document).ready(function(){
	$('.filtermenu a').on("click",function(e){
		 e.stopPropagation();
		 
		 // remove active classes, add current classes
		 $('.filtermenu').removeClass("current").removeClass("active");
		 $(this).parent().addClass("current");
		 
		 $("#qtype option").attr("selected",""); // reset all selected
		 $("#qtype option[value=" + $(this).data("filter") + "]").attr('selected','selected'); // select filter
		 
		 placeholder = "<?php echo $LANG == 'en' ? 'Search by ': 'Rechercher par '; ?>";
		 filteredby = $(this).data("filter");
		 
		 // Change the search type
		 document.getElementById('catalogueSearchType').value = filteredby;

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

    // Google Scholar Search
    $("#searchArticles").on("click", function(){
        $('#gscholar').submit();
    });
	
	// Databases Search
	// Redirect to this URL (on "Search" Databases)
	var URLforDB = "https://biblio.laurentian.ca/research/<?php if($LANG == 'fr') echo "fr/"; ?>databases-a-z";
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

            
            $('#libraryHours .jnd').html("<label>JN&nbsp;Desmarais</label>"+information.JND);
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
                op = information.JND.split('-');
            else if(library == 'Archives')
                op = information.Archives.split('-');
            else if(library == 'JWT')
                 op = information.JWT.split('-');
            else if(library == 'UOS')
                 op = information.UoS.split('-');

          
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
(function() {
  var x = document.createElement("script"); x.type = "text/javascript"; x.async = true;
  x.src = (document.location.protocol === "https:" ? "https://" : "http://") + "ca.libraryh3lp.com/js/libraryh3lp.js?<?php echo $LANG == 'en' ? '643' : '531'; ?>";
  var y = document.getElementsByTagName("script")[0]; y.parentNode.insertBefore(x, y);
})();
</script>
