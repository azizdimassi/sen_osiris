<?php
/*
 *
 *    Copyright 2008,2009 Maarch
 *
 *  This file is part of Maarch Framework.
 *
 *   Maarch Framework is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Maarch Framework is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
 */
if (!defined('_APA'))
    define( '_APA', 'Archivage Physique');
if (!defined('_ADMIN_APA'))
    define( '_ADMIN_APA', 'Administration de l&rsquo; archivage physique');
if (!defined('_ADMIN_APA_DESC'))
    define( '_ADMIN_APA_DESC', 'Administrer l&rsquo;archivage physique avanc&eacute;, les diff&eacute;rents sites de conservation ainsi que les emplacements.');

if (!defined('_BOXES_IN_THE_DEPARTMENT'))
    define( '_BOXES_IN_THE_DEPARTMENT', 'boite(s) associ&eacute;(s) &agrave; l&rsquo;entit&eacute;');
if (!defined('_CONTAINERS_IN_THE_DEPARTMENT'))
    define( '_CONTAINERS_IN_THE_DEPARTMENT', 'UC associ&eacute;(s) &agrave; l&rsquo;entit&eacute;');
if (!defined('_HEADERS_IN_THE_DEPARTMENT'))
    define( '_HEADERS_IN_THE_DEPARTMENT', 'archive(s) associ&eacute;(s) &agrave; l&rsquo;entit&eacute;');
if (!defined('_NATURES_IN_THE_DEPARTMENT'))
    define( '_NATURES_IN_THE_DEPARTMENT', 'nature(s) d&rsquo;archive(s) associ&eacute;(s) &agrave; l&rsquo;entit&eacute;');
if (!defined('_SITES_IN_THE_DEPARTMENT'))
    define( '_SITES_IN_THE_DEPARTMENT', 'site(s) d&rsquo;archivage associ&eacute;(s) &agrave; l&rsquo;entit&eacute;');

/*********************** Manage APA ***********************************/
if (!defined('_MANAGE_APA'))
    define( '_MANAGE_APA', 'Gestion des archives physiques');
if (!defined('_MANAGE_APA_DESC'))
    define( '_MANAGE_APA_DESC', 'Gestion des archives (cr&eacute;ation, mise &agrave; jour, positionnement, &eacute;limination...)');
if (!defined('_MANAGE_APA_ADD'))
    define( '_MANAGE_APA_ADD', 'Cr&eacute;ation d&rsquo;archive');
if (!defined('_MANAGE_APA_ADD_HEADER'))
    define( '_MANAGE_APA_ADD_HEADER', 'Cr&eacute;ation d&rsquo;archive');
if (!defined('_MANAGE_APA_UPDATE_HEADER'))
    define( '_MANAGE_APA_UPDATE_HEADER', 'Mise &agrave; jour d&rsquo;archive');
if (!defined('_MANAGE_APA_DELETE_HEADER'))
    define( '_MANAGE_APA_DELETE_HEADER', 'Elimination d&rsquo;archive');
if (!defined('_MANAGE_APA_POSITION'))
    define( '_MANAGE_APA_POSITION', 'Positionnement');
if (!defined('_MANAGE_APA_POSITION_SHORT'))
    define( '_MANAGE_APA_POSITION_SHORT', 'Position');
if (!defined('_MANAGE_APA_VIEW'))
    define( '_MANAGE_APA_VIEW', 'Consultation position archive');
if (!defined('_MANAGE_APA_AUTO_RETURN'))
    define( '_MANAGE_APA_AUTO_RETURN', 'R&eacute;int&eacute;gration');
if (!defined('_MANAGE_APA_PRINT'))
    define( '_MANAGE_APA_PRINT', 'Editions');
if (!defined('_MANAGE_APA_CUSTOMER'))
    define( '_MANAGE_APA_CUSTOMER', 'Service versant');
if (!defined('_MANAGE_APA_DISTRIBUTOR'))
    define( '_MANAGE_APA_DISTRIBUTOR', 'Concessionnaire');
if (!defined('_HAVE_TO_SELECT_CUTOMER'))
    define( '_HAVE_TO_SELECT_CUTOMER', 'Vous devez s&eacute;lectionner un client');
if (!defined('_MANAGE_APA_CHOOSE_CUSTOMER'))
    define( '_MANAGE_APA_CHOOSE_CUSTOMER', 'Choisir un client');
if (!defined('_MANAGE_APA_YEAR1'))
    define( '_MANAGE_APA_YEAR1', 'Ann&eacute;e 1');
if (!defined('_MANAGE_APA_YEAR2'))
    define( '_MANAGE_APA_YEAR2', 'Ann&eacute;e 2');
if (!defined('_MANAGE_APA_DATE1'))
    define('_MANAGE_APA_DATE1', 'Date 1');
if (!defined('_MANAGE_APA_DATE2'))
    define('_MANAGE_APA_DATE2', 'Date 2');
if (!defined('_MANAGE_APA_NUM_UA'))
    define( '_MANAGE_APA_NUM_UA', 'N&deg; Unit&eacute; archive');
if (!defined('_MANAGE_APA_NUM_UA_SHORT'))
    define( '_MANAGE_APA_NUM_UA_SHORT', 'N&deg; UA');
if (!defined('_MANAGE_APA_NUM_UC'))
    define( '_MANAGE_APA_NUM_UC', 'N&deg; Unit&eacute; conditionnement');
if (!defined('_MANAGE_APA_NUM_UC_SHORT'))
    define( '_MANAGE_APA_NUM_UC_SHORT', 'N&deg; UC');
if (!defined('_MANAGE_APA_NUM_BOX'))
    define( '_MANAGE_APA_NUM_BOX', 'N&deg; de boite');
if (!defined('_MANAGE_APA_DESTRUCTION_DATE'))
    define( '_MANAGE_APA_DESTRUCTION_DATE', 'Date d&rsquo;&eacute;limination');
if (!defined('_MANAGE_APA_ALLOW_DATE'))
    define( '_MANAGE_APA_ALLOW_DATE', 'Date de communicabilit&eacute;');
if (!defined('_MANAGE_APA_HEADER_DESC'))
    define( '_MANAGE_APA_HEADER_DESC', 'Libell&eacute; de recherche');
if (!defined('_MANAGE_APA_HEADER_UPDATED'))
    define( '_MANAGE_APA_HEADER_UPDATED', 'Archive modifi&eacute;e');
if (!defined('_MANAGE_APA_HEADER_DELETED'))
    define( '_MANAGE_APA_HEADER_DELETED', 'Archive supprim&eacute;e');
if (!defined('_MANAGE_APA_HEADER_RESERVED'))
    define( '_MANAGE_APA_HEADER_RESERVED', 'Archive r&eacute;serv&eacute;e');
if (!defined('_MANAGE_APA_HEADER_ADDED'))
    define( '_MANAGE_APA_HEADER_ADDED', 'Archive ajout&eacute;e');
if (!defined('_MANAGE_APA_UC_CREATED'))
    define( '_MANAGE_APA_UC_CREATED', 'Unit&eacute; de conditionnement cr&eacute;&eacute;e');
if (!defined('_MANAGE_APA_UA_CREATED'))
    define( '_MANAGE_APA_UA_CREATED', 'Unit&eacute; d&rsquo;archive cr&eacute;&eacute;e');
if (!defined('_MANAGE_APA_ADD_UC'))
    define( '_MANAGE_APA_ADD_UC', 'Positionnement d&rsquo;unit&eacute;s de conditionnement');
if (!defined('_MANAGE_APA_FROM_UC'))
    define( '_MANAGE_APA_FROM_UC', 'Du N&deg;');
if (!defined('_MANAGE_APA_TO_UC'))
    define( '_MANAGE_APA_TO_UC', 'Au N&deg;');
if (!defined('_MANAGE_APA_UC_BEGIN'))
    define( '_MANAGE_APA_UC_BEGIN', 'UC de d&eacute;but');
if (!defined('_MANAGE_APA_UC_END'))
    define( '_MANAGE_APA_UC_END', 'UC de fin');
if (!defined('_MANAGE_APA_UC_EMPTY'))
    define( '_MANAGE_APA_UC_EMPTY', 'L&rsquo;unit&eacute; de conditionnement est vide');
if (!defined('_MANAGE_APA_UC_POSITIONED'))
    define( '_MANAGE_APA_UC_POSITIONED', 'Unit&eacute; de conditionnement positionn&eacute;e');
if (!defined('_MANAGE_APA_UC_ALREADY_POSITIONED'))
    define( '_MANAGE_APA_UC_ALREADY_POSITIONED', 'Cette unit&eacute; de conditionnement est d&eacute;ja positionn&eacute;e');
if (!defined('_MANAGE_APA_UC_ALREADY_POSITIONED_CONTINUE'))
    define( '_MANAGE_APA_UC_ALREADY_POSITIONED_CONTINUE', 'Souhaitez vous la repositionner?');
if (!defined('_MANAGE_APA_UC_POSITION_CANCELED'))
    define( '_MANAGE_APA_UC_POSITION_CANCELED', 'Positionnement annul&eacute;');
if (!defined('_MANAGE_APA_UC_NO_ACCESS'))
    define( '_MANAGE_APA_UC_NO_ACCESS', 'Vous n&rsquo;avez pas les droits sur cette unit&eacute; de conditionnement');
if (!defined('_MANAGE_APA_UA_NO_ACCESS'))
    define( '_MANAGE_APA_UA_NO_ACCESS', 'Vous n&rsquo;avez pas les droits sur cette unit&eacute; d&rsquo;archive');
if (!defined('_MANAGE_APA_UC_DOES_NOT_EXISTS'))
    define( '_MANAGE_APA_UC_DOES_NOT_EXISTS', 'Cette UC n&rsquo;existe pas');
if (!defined('_MANAGE_APA_UA_DOES_NOT_EXISTS'))
    define( '_MANAGE_APA_UA_DOES_NOT_EXISTS', 'Cette UA n&rsquo;existe pas');
if (!defined('_MANAGE_APA_SEARCH'))
    define( '_MANAGE_APA_SEARCH', 'Consultation d&rsquo;archives');
if (!defined('_MANAGE_APA_ADVANCED_SEARCH'))
    define( '_MANAGE_APA_ADVANCED_SEARCH', 'Recherche avanc&eacute;e');
if (!defined('_MANAGE_APA_ADVANCED_SEARCH_ALL'))
    define( '_MANAGE_APA_ADVANCED_SEARCH_ALL', '<b>tous</b> les mots suivants');
if (!defined('_MANAGE_APA_ADVANCED_SEARCH_WORD'))
    define( '_MANAGE_APA_ADVANCED_SEARCH_WORD', 'le mot');
if (!defined('_MANAGE_APA_ADVANCED_SEARCH_IS_IGNORED'))
    define( '_MANAGE_APA_ADVANCED_SEARCH_IS_IGNORED', 'est ignor&eacute;');
if (!defined('_MANAGE_APA_ADVANCED_SEARCH_EXACT'))
    define( '_MANAGE_APA_ADVANCED_SEARCH_EXACT', 'cette <b>expression exacte</b>');
if (!defined('_MANAGE_APA_ADVANCED_SEARCH_ONE'))
    define( '_MANAGE_APA_ADVANCED_SEARCH_ONE', '<b>au moins un</b> des mots suivants');
if (!defined('_MANAGE_APA_ADVANCED_SEARCH_NONE'))
    define( '_MANAGE_APA_ADVANCED_SEARCH_NONE', '<b>aucun</b> des mots suivants');
if (!defined('_MANAGE_APA_SIMPLE_SEARCH'))
    define( '_MANAGE_APA_SIMPLE_SEARCH', 'Recherche simple');
if (!defined('_MANAGE_APA_SEARCH_WARNING_DESC_LENGTH'))
    define( '_MANAGE_APA_SEARCH_WARNING_DESC_LENGTH', 'La recherche sur la d&eacute;scription doit faire au minimum 4 caract&egrave;res ');
if (!defined('_MANAGE_APA_ARCHIVE_INFO'))
    define( '_MANAGE_APA_ARCHIVE_INFO', 'Informations sur l&rsquo;archive');
if (!defined('_MANAGE_APA_DEPOSIT_DATE'))
    define( '_MANAGE_APA_DEPOSIT_DATE', 'Date de versement');
if (!defined('_MANAGE_APA_TO_DATE'))
    define( '_MANAGE_APA_TO_DATE', 'Au');
if (!defined('_MANAGE_APA_UA_DIFFERENT_DATE'))
    define( '_MANAGE_APA_UA_DIFFERENT_DATE', 'Infos : Les dates d&rsquo;&eacute;liminations des archives d&rsquo;une m&ecirc;me UC doivent &ecirc;tre les m&ecirc;mes');
if (!defined('_MANAGE_APA_UA_NOT_IN_UC'))
    define( '_MANAGE_APA_UA_NOT_IN_UC', 'Cette unit&eacute; d&rsquo;archive ne se trouve pas dans cette unit&eacute; de conditionnement');
if (!defined('_MANAGE_APA_VIEW_HEADER'))
    define( '_MANAGE_APA_VIEW_HEADER', 'Afficher');


//Archive
if (!defined('_ARCHIVE'))
    define( '_ARCHIVE','archive');
if (!defined('_FOUND_ARCHIVES'))
    define( '_FOUND_ARCHIVES','archive(s) trouv&eacute;e(s)');
if (!defined('_THE_ARCHIVE'))
    define( '_THE_ARCHIVE','L&rsquo;archive');
if (!defined('_NUM_ARCHIVE'))
    define( '_NUM_ARCHIVE','N&deg; archive');
if (!defined('_DATE_CREATION_ARCHIVE'))
    define( '_DATE_CREATION_ARCHIVE','Date de cr&eacute;ation');
if (!defined('_NO_ARCHIVE_CORRESPOND_TO_IDENTIFIER'))
    define( '_NO_ARCHIVE_CORRESPOND_TO_IDENTIFIER','Aucune archive ne correspond &agrave; cet identifiant');
if (!defined('_ARCHIVE_PROPERTIES'))
    define( '_ARCHIVE_PROPERTIES','Propri&eacute;t&eacute;s de l&rsquo;archive');
if (!defined('_UPDATE_ARCHIVE'))
    define( '_UPDATE_ARCHIVE','Mise &agrave; jour archive');
if (!defined('_ARCHIVE_HISTORY'))
    define( '_ARCHIVE_HISTORY','Historique de l&rsquo;archive');
if (!defined('_ARCHIVE_NOT_POSITIONED'))
    define( '_ARCHIVE_NOT_POSITIONED','Archive non positionn&eacute;e');
if (!defined('_ARCHIVE_STATUS'))
    define( '_ARCHIVE_STATUS','Etat');
if (!defined('_ARCHIVE_STATUS_AVAILABLE'))
    define( '_ARCHIVE_STATUS_AVAILABLE','Disponible (non positionn&eacute;e)');
if (!defined('_ARCHIVE_STATUS_RESERVED'))
    define( '_ARCHIVE_STATUS_RESERVED','R&eacute;serv&eacute;e');
if (!defined('_ARCHIVE_STATUS_OUT'))
    define( '_ARCHIVE_STATUS_OUT','Non disponible');
if (!defined('_ARCHIVE_STATUS_POSITIONED'))
    define( '_ARCHIVE_STATUS_POSITIONED','Positionn&eacute;e');
if (!defined('_WARNING_DELETE_RESERVED_ARCHIVE'))
    define( '_WARNING_DELETE_RESERVED_ARCHIVE', 'Cette archive est en cours de r&eacute;servation impossible de la supprimer');
if (!defined('_WARNING_ALREADY_RESERVED_ARCHIVE'))
    define( '_WARNING_ALREADY_RESERVED_ARCHIVE', 'Cette archive est d&eacute;ja r&eacute;serv&eacute;e');
if (!defined('_WARNING_ALREADY_EXIST_ARCHIVE'))
    define( '_WARNING_ALREADY_EXIST_ARCHIVE', 'Cette archive existe d&eacute;ja');
if (!defined('_WARNING_UPDATE_LOCKED_ARCHIVE'))
    define( '_WARNING_UPDATE_LOCKED_ARCHIVE', 'Cette archive est v&eacute;rouill&eacute;e impossible de la modifier');
if (!defined('_WARNING_CHOOSE_AT_LEAST_ONE_ARCHIVE'))
    define( '_WARNING_CHOOSE_AT_LEAST_ONE_ARCHIVE', 'Vous devez choisir au moins une archive !');
if (!defined('_WARNING_NON_OUTED_ARCHIVE'))
    define( '_WARNING_NON_OUTED_ARCHIVE', 'Cette archive n&rsquo;est pas pr&eacute;lev&eacute;e !');
if (!defined('_ARCHIVE_RESERVATION'))
    define( '_ARCHIVE_RESERVATION','R&eacute;servation d&rsquo;archive');
if (!defined('_ARCHIVE_RESERVATION_DESC'))
    define( '_ARCHIVE_RESERVATION_DESC','Permet a un client de r&eacute;server une archive');
if (!defined('_ARCHIVE_RESERVATION_BATCH'))
    define( '_ARCHIVE_RESERVATION_BATCH','Lot de r&eacute;servation');
if (!defined('_ARCHIVE_RESERVED'))
    define( '_ARCHIVE_RESERVED','archive(s) r&eacute;serv&eacute;e(s)');
if (!defined('_ARCHIVE_DEDUCTION'))
    define( '_ARCHIVE_DEDUCTION','Pr&eacute;l&egrave;vement d&rsquo;archive');
if (!defined('_ARCHIVE_DEDUCTED'))
    define( '_ARCHIVE_DEDUCTED','Archive pr&eacute;lev&eacute;e');
if (!defined('_ARCHIVE_RETURN'))
    define( '_ARCHIVE_RETURN','R&eacute;int&eacute;gration d&rsquo;archive');
if (!defined('_ARCHIVE_RETURNED'))
    define( '_ARCHIVE_RETURNED','Archive r&eacute;int&eacute;gr&eacute;e');
if (!defined('_ARCHIVE_RESERVE'))
    define( '_ARCHIVE_RESERVE','R&eacute;server l&rsquo;archive');
if (!defined('_LEGEND'))
    define( '_LEGEND','L&eacute;gende');


/*********************** Sites ***********************************/
if (!defined('_SITE'))
    define( '_SITE', 'Site');
if (!defined('_SITES'))
    define( '_SITES', 'Site(s)');
if (!defined('_ALL_SITES'))
    define( '_ALL_SITES', 'Tous');
if (!defined('_THE_SITE'))
    define( '_THE_SITE', 'Le site');
if (!defined('_CHOOSE_SITE'))
    define( '_CHOOSE_SITE', 'Choisir un site');
if (!defined('_HAVE_TO_SELECT_SITE'))
    define( '_HAVE_TO_SELECT_SITE', 'Vous devez s&eacute;lectionner un site');
if (!defined('_MANAGE_SITE'))
    define( '_MANAGE_SITE','G&eacute;rer les sites');
if (!defined('_MANAGE_SITE_DESC'))
    define( '_MANAGE_SITE_DESC','G&eacute;rer les sites de conservation');
if (!defined('_SITE_LIST'))
    define( '_SITE_LIST', 'Liste des sites');
if (!defined('_SITE_DOES_NOT_EXISTS'))
    define( '_SITE_DOES_NOT_EXISTS', 'Ce site n&rsquo;existe pas');
if (!defined('_ADD_SITE'))
    define( '_ADD_SITE','Ajouter un site');
if (!defined('_SITE_DESC'))
    define( '_SITE_DESC','Description du site');
if (!defined('_SITE_ADDITION'))
    define( '_SITE_ADDITION', 'Ajout de site');
if (!defined('_SITE_ADDED'))
    define( '_SITE_ADDED', 'Site ajout&eacute;');
if (!defined('_SITE_MODIFICATION'))
    define( '_SITE_MODIFICATION', 'Modification de site');
if (!defined('_MODIFY_SITE'))
    define( '_MODIFY_SITE', 'Modifier site');
if (!defined('_SITE_DELETED'))
    define( '_SITE_DELETED', 'Site supprim&eacute;');
if (!defined('_SITE_MODIFIED'))
    define( '_SITE_MODIFIED', 'Site modifi&eacute;');
if (!defined('_SITE_UPDATED'))
    define( '_SITE_UPDATED', 'Site modifi&eacute;');
if (!defined('_CHOOSE_ENTITY'))
    define( '_CHOOSE_ENTITY', 'Choisir une entit&eacute;');
if (!defined('_SITE_ALREADY_EXISTS'))
    define( '_SITE_ALREADY_EXISTS', 'Site de conservation existant');
if (!defined('_WARNING_USED_SITE'))
    define( '_WARNING_USED_SITE', 'Ce site est en cours d&rsquo;exploitation, son identifiant ne peut &ecirc;tre modifi&eacute;');
if (!defined('_WARNING_DELETE_USED_SITE'))
    define( '_WARNING_DELETE_USED_SITE', 'Ce site est en cours d&rsquo;exploitation, impossible de le supprimer');

/*********************** Container Types ***********************************/
if (!defined('_CONTAINER_TYPES'))
    define( '_CONTAINER_TYPES', 'Type(s) d&rsquo;UC');
if (!defined('_MANAGE_CONTAINER_TYPES'))
    define( '_MANAGE_CONTAINER_TYPES','G&eacute;rer les types d&rsquo;UC');
if (!defined('_MANAGE_CONTAINER_TYPES_DESC'))
    define( '_MANAGE_CONTAINER_TYPES_DESC','G&eacute;rer les types d&rsquo;UC ainsi que leurs dimensions');
if (!defined('_CONTAINER_TYPES_SIZEX'))
    define( '_CONTAINER_TYPES_SIZEX', 'Dimension (x)(m)');
if (!defined('_CONTAINER_TYPES_SIZEY'))
    define( '_CONTAINER_TYPES_SIZEY', 'Dimension (y)(m)');
if (!defined('_CONTAINER_TYPES_SIZEZ'))
    define( '_CONTAINER_TYPES_SIZEZ', 'Dimension (z)(m)');
if (!defined('_ADD_CONTAINER_TYPES'))
    define( '_ADD_CONTAINER_TYPES','Ajouter un type d&rsquo;UC');
if (!defined('_CONTAINER_TYPES_LIST'))
    define( '_CONTAINER_TYPES_LIST', 'Liste des types d&rsquo;UC');
if (!defined('_ALL_CONTAINER_TYPES'))
    define( '_ALL_CONTAINER_TYPES', 'Tous');
if (!defined('_CONTAINER_TYPES_DOES_NOT_EXISTS'))
    define( '_CONTAINER_TYPES_DOES_NOT_EXISTS', 'Ce Type d&rsquo;UC n&rsquo;existe pas');
if (!defined('_CONTAINER_TYPES_ADDITION'))
    define( '_CONTAINER_TYPES_ADDITION', 'Ajout de type d&rsquo;UC');
if (!defined('_CONTAINER_TYPES_ADDED'))
    define( '_CONTAINER_TYPES_ADDED', 'Type d&rsquo;UC ajout&eacute;');
if (!defined('_CONTAINER_TYPES_MODIFICATION'))
    define( '_CONTAINER_TYPES_MODIFICATION', 'Modification du type d&rsquo;UC');
if (!defined('_MODIFY_CONTAINER_TYPES'))
    define( '_MODIFY_CONTAINER_TYPES', 'Modifier le type d&rsquo;UC');
if (!defined('_CONTAINER_TYPES_DESC'))
    define( '_CONTAINER_TYPES_DESC','Description du type d&rsquo;UC');
if (!defined('_THE_CONTAINER_TYPES'))
    define( '_THE_CONTAINER_TYPES', 'type d&rsquo;UC');
if (!defined('_CHOOSE_CONTAINER_TYPES'))
    define( '_CHOOSE_CONTAINER_TYPES', 'Choisir un type d&rsquo;UC');
if (!defined('_WARNING_USED_CONTAINER_TYPES'))
    define( '_WARNING_USED_CONTAINER_TYPES', 'Ce type d&rsquo;UC est en cours d&rsquo;utilisation, son identifiant ne peut &ecirc;tre modifi&eacute;');
if (!defined('_WARNING_DELETE_USED_CONTAINER_TYPES'))
    define( '_WARNING_DELETE_USED_CONTAINER_TYPES', 'Ce type d&rsquo;UC est en cours d&rsquo;utilisation, impossible de le supprimer');
if (!defined('_CONTAINER_TYPES_DELETED'))
    define( '_CONTAINER_TYPES_DELETED', 'type d&rsquo;UC supprim&eacute;');
if (!defined('_CONTAINER_TYPES_ALREADY_EXISTS'))
    define( '_CONTAINER_TYPES_ALREADY_EXISTS', 'type d&rsquo;UC existant');
if (!defined('_CONTAINER_TYPES_UPDATED'))
    define( '_CONTAINER_TYPES_UPDATED', 'type d&rsquo;UC modifi&eacute;');

/*********************** Natures  of archives***********************************/

if (!defined('_NATURE'))
    define( '_NATURE', 'Nature');
if (!defined('_TYPE'))
    define( '_TYPE', 'Type');
if (!defined('_NATURES'))
    define( '_NATURES', 'Nature(s) d&rsquo;archives');
if (!defined('_MANAGE_NATURE'))
    define( '_MANAGE_NATURE','G&eacute;rer les natures d&rsquo;archives');
if (!defined('_MANAGE_NATURE_DESC'))
    define( '_MANAGE_NATURE_DESC','G&eacute;rer les natures d&rsquo;archives associ&eacute;es &agrave; une entit&eacute;');
if (!defined('_NATURE_RETENTION'))
    define( '_NATURE_RETENTION','Dur&eacute;e de conservation (en nombre d&rsquo;ann&eacute;e)');
if (!defined('_NATURE_RETENTION_SHORT'))
    define( '_NATURE_RETENTION_SHORT','Dur&eacute;e de conservation');
if (!defined('_NATURES_LIST'))
    define( '_NATURES_LIST', 'Liste des  natures d&rsquo;archives');
if (!defined('_ADD_NATURE'))
    define( '_ADD_NATURE','Ajouter une nature d&rsquo;archives');
if (!defined('_ALL_NATURES'))
    define( '_ALL_NATURES', 'Toutes');
if (!defined('_NATURE_DOES_NOT_EXISTS'))
    define( '_NATURE_DOES_NOT_EXISTS', 'Cete nature n&rsquo;existe pas');
if (!defined('_NATURE_ADDITION'))
    define( '_NATURE_ADDITION', 'Ajout de nature d&rsquo;archives');
if (!defined('_NATURE_ADDED'))
    define( '_NATURE_ADDED', 'Nature ajout&eacute;e');
if (!defined('_NATURE_MODIFICATION'))
    define( '_NATURE_MODIFICATION', 'Modification de nature');
if (!defined('_MODIFY_NATURE'))
    define( '_MODIFY_NATURE', 'Modifier la nature');
if (!defined('_CHOOSE_NATURE'))
    define( '_CHOOSE_NATURE','Choisir une nature');
if (!defined('_NATURE_DESC'))
    define( '_NATURE_DESC','Description de la nature');
if (!defined('_THE_NATURE'))
    define( '_THE_NATURE', 'La nature d&rsquo;archives');
if (!defined('_WARNING_USED_NATURE'))
    define( '_WARNING_USED_NATURE', 'Cete nature d&rsquo;archives est en cours d&rsquo;utilisation, son identifiant ne peut &ecirc;tre modifi&eacute;');
if (!defined('_WARNING_DELETE_USED_NATURE'))
    define( '_WARNING_DELETE_USED_NATURE', 'Cette nature d&rsquo;archives est en cours d&rsquo;utilisation, impossible de la supprimer');
if (!defined('_NATURE_DELETED'))
    define( '_NATURE_DELETED', 'Nature d&rsquo;archives supprim&eacute;e');
if (!defined('_NATURE_ALREADY_EXISTS'))
    define( '_NATURE_ALREADY_EXISTS', 'Nature d&rsquo;archives existante');
if (!defined('_NATURE_UPDATED'))
    define( '_NATURE_UPDATED', 'Nature d&rsquo;archives modifi&eacute;e');
if (!defined('_AUTORIZED_NATURE'))
    define( '_AUTORIZED_NATURE', 'Nature d&rsquo;archives autoris&eacute;e');
if (!defined('_SUSPENDED_NATURE'))
    define( '_SUSPENDED_NATURE', 'Nature d&rsquo;archives suspendue');

/*********************** Positions ***********************************/
if (!defined('_POSITIONS'))
    define( '_POSITIONS','Positions des emplacements');
if (!defined('_MANAGE_POSITION'))
    define( '_MANAGE_POSITION','G&eacute;rer les emplacements');
if (!defined('_MANAGE_POSITION_DESC'))
    define( '_MANAGE_POSITION_DESC', 'G&eacute;rer les positions des emplacements, d&eacute;finition du site (Boxs / all&eacute;es /&eacute;tages / capacit&eacute;).');
if (!defined('_ADD_POSITIONS'))
    define( '_ADD_POSITIONS','Ajouter des positions');
if (!defined('_ADD_NEW_POSITIONS'))
    define( '_ADD_NEW_POSITIONS','Ajouter des positions suppl&eacute;mentaires');
if (!defined('_POSITIONS_SITE'))
    define( '_POSITIONS_SITE', 'Site de conservation');
if (!defined('_POSITIONS_ROWS'))
    define( '_POSITIONS_ROWS', 'All&eacute;e(s)');
if (!defined('_POSITIONS_ROW'))
    define( '_POSITIONS_ROW', 'All&eacute;e');
if (!defined('_POSITIONS_CHOOSE_ROW'))
    define( '_POSITIONS_CHOOSE_ROW', 'Selectionnez une all&eacute;e');
if (!defined('_POSITIONS_ROW_BEGIN'))
    define( '_POSITIONS_ROW_BEGIN', 'All&eacute;e de d&eacute;but');
if (!defined('_POSITIONS_ROW_END'))
    define( '_POSITIONS_ROW_END', 'All&eacute;e de fin');
if (!defined('_POSITIONS_FROM_ROW'))
    define( '_POSITIONS_FROM_ROW', 'De l&rsquo;All&eacute;e');
if (!defined('_POSITIONS_TO_ROW'))
    define( '_POSITIONS_TO_ROW', '&agrave; l&rsquo;All&eacute;e');
if (!defined('_POSITIONS_CHOOSE_COL'))
    define( '_POSITIONS_CHOOSE_COL', 'Selectionnez une colonne');
if (!defined('_POSITIONS_COL'))
    define( '_POSITIONS_COL', 'Colonne');
if (!defined('_POSITIONS_COLS'))
    define( '_POSITIONS_COLS', 'Colonne(s)');
if (!defined('_POSITIONS_FROM_COL'))
    define( '_POSITIONS_FROM_COL', 'De la colonne');
if (!defined('_POSITIONS_TO_COL'))
    define( '_POSITIONS_TO_COL', '&agrave; la colonne');
if (!defined('_POSITIONS_COL_END'))
    define( '_POSITIONS_COL_END', 'Colonne de fin');
if (!defined('_POSITIONS_NUMBER_OF_LEVEL'))
    define( '_POSITIONS_NUMBER_OF_LEVEL', 'Nombre de niveaux');
if (!defined('_POSITIONS_CHOOSE_LEVEL'))
    define( '_POSITIONS_CHOOSE_LEVEL', 'Selectionnez un niveau');
if (!defined('_POSITIONS_LEVEL'))
    define( '_POSITIONS_LEVEL', 'Niveau');
if (!defined('_POSITIONS_FROM_LEVEL'))
    define( '_POSITIONS_FROM_LEVEL', 'Du niveau');
if (!defined('_POSITIONS_TO_LEVEL'))
    define( '_POSITIONS_TO_LEVEL', 'Au niveau');
if (!defined('_POSITIONS_LEVEL_END'))
    define( '_POSITIONS_LEVEL_END', 'Niveau de fin');
if (!defined('_POSITIONS_CAPACITY'))
    define( '_POSITIONS_CAPACITY', 'Capacit&eacute;');
if (!defined('_POSITIONS_MAX_UC'))
    define( '_POSITIONS_MAX_UC', 'Capacit&eacute; max.');
if (!defined('_POSITIONS_AVAILABLE_UC'))
    define( '_POSITIONS_AVAILABLE_UC', 'Capacit&eacute; restante');
if (!defined('_MODIFY_POSITIONS'))
    define( '_MODIFY_POSITIONS', 'Modification des capacit&eacute;s');
if (!defined('_POSITIONS_ADDITION'))
    define( '_POSITIONS_ADDITION', 'Ajout de positions');
if (!defined('_POSITIONS_ADDED'))
    define( '_POSITIONS_ADDED', 'Position(s) ajout&eacute;e(s)');
if (!defined('_POSITIONS_MODIFICATION'))
    define( '_POSITIONS_MODIFICATION', 'Modification de capacit&eacute;');
if (!defined('_POSITIONS_ALWAYS_EXIST'))
    define( '_POSITIONS_ALWAYS_EXIST', 'position(s) existe(nt) d&eacute;j&agrave; ');
if (!defined('_POSITIONS_ROWS_ALWAYS_EXIST'))
    define( '_POSITIONS_ROWS_ALWAYS_EXIST', 'ce(s) all&eacute;e(s) existe(nt) d&eacute;j&agrave; ');
if (!defined('_POSITIONS_IS_FULL'))
    define( '_POSITIONS_IS_FULL', 'La capacit&eacute de la position est atteinte');
if (!defined('_POSITIONS_CHOOSE_ANOTHER'))
    define( '_POSITIONS_CHOOSE_ANOTHER', 'Veuillez en choisir une autre');
if (!defined('_POSITIONS_DONT_EXIST'))
    define( '_POSITIONS_DONT_EXIST', 'La position n&rsquo;existe pas ');
if (!defined('_POSITIONS_MUST_BE_CREATE'))
    define( '_POSITIONS_MUST_BE_CREATE', 'Un administrateur doit d&rsquo;abord cr&eacute;er la position');
if (!defined('_POSITIONS_UPDATED'))
    define( '_POSITIONS_UPDATED', 'Capacit&eacute; position modifi&eacute;e');
if (!defined('_POSITIONS_DELETION'))
    define( '_POSITIONS_DELETION', 'Suppression de positions');
if (!defined('_DELETE_POSITIONS'))
    define( '_DELETE_POSITIONS', 'Supprimer des positions');
if (!defined('_POSITIONS_FOR_LEVEL'))
    define( '_POSITIONS_FOR_LEVEL', 'Niveau');
if (!defined('_POSITIONS_DELETED'))
    define( '_POSITIONS_DELETED', 'position(s) supprim&eacute;e(s)');
if (!defined('_POSITIONS_AVAILABLE'))
    define( '_POSITIONS_AVAILABLE', 'Positions disponibles');
if (!defined('_POSITIONS_OCCUPATION_RATE'))
    define( '_POSITIONS_OCCUPATION_RATE', 'Pourcentage d&rsquo;occupation');
if (!defined('_POSITIONS_VIEW_STATUS_EMPTY'))
    define( '_POSITIONS_VIEW_STATUS_EMPTY', 'Libre');
if (!defined('_POSITIONS_VIEW_STATUS_FULL'))
    define( '_POSITIONS_VIEW_STATUS_FULL', 'Plein');
if (!defined('_POSITIONS_VIEW_STATUS_ACTIVE'))
    define( '_POSITIONS_VIEW_STATUS_ACTIVE', 'Actif');
if (!defined('_POSITIONS_VIEW_STATUS_HALF'))
    define( '_POSITIONS_VIEW_STATUS_HALF', 'A moiti&eacute;');

/*********************** APA print ***********************************/

if (!defined('_APA_PRINT_CLICK_LINE_BELOW'))
    define( '_APA_PRINT_CLICK_LINE_BELOW', 'Veuillez cliquez sur une des lignes ci&ndash;dessous pour voir les &eacute;ditions correspondantes');
if (!defined('_APA_PRINT_INVENTORY_LIST_CUSTMER_NAT'))
    define( '_APA_PRINT_INVENTORY_LIST_CUSTMER_NAT', 'Inventaire par client/nature/ann&eacute;e');
if (!defined('_APA_PRINT_INVENTORY_LIST_CUSTOMER_UC'))
    define( '_APA_PRINT_INVENTORY_LIST_CUSTOMER_UC', 'Inventaire par client/UC');
if (!defined('_APA_PRINT_DOCKET_SEARCH'))
    define( '_APA_PRINT_DOCKET_SEARCH', 'Bordereaux de recherche');
if (!defined('_APA_PRINT_DOCKET_RESERVATION'))
    define( '_APA_PRINT_DOCKET_RESERVATION', 'Bordereaux de r&eacute;servation');
if (!defined('_APA_PRINT_RETURN_BY_PERIOD'))
    define( '_APA_PRINT_RETURN_BY_PERIOD', 'R&eacute;int&eacute;grations pour une p&eacute;riode donn&eacute;e');
if (!defined('_APA_PRINT_OUT_NOT_RETURN'))
    define( '_APA_PRINT_OUT_NOT_RETURN', 'Sorties non r&eacute;int&eacute;gr&eacute;es');
if (!defined('_APA_PRINT_UC_DESTRUCTION'))
    define( '_APA_PRINT_UC_DESTRUCTION', 'Proposition de destruction d&rsquo;UC');
if (!defined('_APA_PRINT_DESTRUCTION_PRINTING'))
    define( '_APA_PRINT_DESTRUCTION_PRINTING', 'Editions des &eacute;liminations');
if (!defined('_APA_PRINT_RESERVATION_BY_CUSTOMER_DATE'))
    define( '_APA_PRINT_RESERVATION_BY_CUSTOMER_DATE', 'Nombre de r&eacute;servations par concessionnaire/client sur une p&eacute;riode donn&eacute;e');
if (!defined('_APA_PRINT_RATIO_BY_SITE_BOX'))
    define( '_APA_PRINT_RATIO_BY_SITE_BOX', 'Ratio de remplissage par concessionnaire/site/box ');
if (!defined('_APA_PRINT_IN_OUT_BY_CUSTOMER_DATE'))
    define( '_APA_PRINT_IN_OUT_BY_CUSTOMER_DATE', 'Entr&eacute;es et sorties par site sur une p&eacute;riode donn&eacute;e par concessionnaire');
if (!defined('_APA_PRINT_NO_EDITION_CORRESPOND_TO_IDENTIFIER'))
    define( '_APA_PRINT_NO_EDITION_CORRESPOND_TO_IDENTIFIER','Aucune &eacute;dition ne correspond &agrave; cet identifiant');
if (!defined('_APA_PRINT_NO_RESULT'))
    define( '_APA_PRINT_NO_RESULT','Aucun r&eacute;sultat');
if (!defined('_APA_PRINT_SERVICE'))
    define( '_APA_PRINT_SERVICE', 'Service');
if (!defined('_APA_PRINT_TOTAL'))
    define( '_APA_PRINT_TOTAL', 'Total');
if (!defined('_APA_PRINT_IN_OUT'))
    define( '_APA_PRINT_IN_OUT', 'Entr&eacute;es/Sorties');
if (!defined('_MANAGE_APA_YEAR'))
    define( '_MANAGE_APA_YEAR', 'Ann&eacute;e');
if (!defined('_MANAGE_APA_NUMBER'))
    define( '_MANAGE_APA_NUMBER', 'Nombre');

/*********************************************/

if (!defined('_MANAGE_APA_IMPORT'))
    define( '_MANAGE_APA_IMPORT', 'Importation de masse');
if (!defined('_MANAGE_APA_IMPORT_FILE'))
    define( '_MANAGE_APA_IMPORT_FILE', 'Fichier d&rsquo;importation');
if (!defined('_MANAGE_APA_IMPORT_SIZE_TOO_LARGE'))
    define( '_MANAGE_APA_IMPORT_SIZE_TOO_LARGE', 'Taille du fichier trop grande');
if (!defined('_MANAGE_APA_IMPORT_UNKNOWN_FILE'))
    define( '_MANAGE_APA_IMPORT_UNKNOWN_FILE', 'Type de fichier non pris en charge');
if (!defined('_MANAGE_APA_IMPORT_UNABLE_TO_OPEN_FILE'))
    define( '_MANAGE_APA_IMPORT_UNABLE_TO_OPEN_FILE', 'Impossible d&rsquo;ouvrir le fichier');
if (!defined('_MANAGE_APA_IMPORT_UNABLE_TO_OPEN_CONF_IMPORT'))
    define( '_MANAGE_APA_IMPORT_UNABLE_TO_OPEN_CONF_IMPORT', 'Impossible d&rsquo;ouvrir le fichier de configuration');
if (!defined('_MANAGE_APA_IMPORT_CHOSE_IMPORT_FILE'))
    define( '_MANAGE_APA_IMPORT_CHOSE_IMPORT_FILE', 'Veuillez s&eacute;lectioner le fichier &agrave; importer');
if (!defined('_MANAGE_APA_IMPORT_FAILURE_OF_UPLOAD'))
    define( '_MANAGE_APA_IMPORT_FAILURE_OF_UPLOAD', 'Il y&rsquo;a eu un prolbl&egrave;me durant le chargement du fichier');
if (!defined('_MANAGE_APA_IMPORT_LINE_NUM'))
    define( '_MANAGE_APA_IMPORT_LINE_NUM', 'Ligne N&deg;');
if (!defined('_MANAGE_APA_IMPORT_WRONG_NUMBER_FIELDS'))
    define( '_MANAGE_APA_IMPORT_WRONG_NUMBER_FIELDS', 'Nombre de champs erron&eacute;s');

/*********************************************/

if (!defined('_MANAGE_APA_TIKET_SLIP'))
    define( '_MANAGE_APA_TIKET_SLIP', 'Bordereau');
if (!defined('_MANAGE_APA_TIKET_NUM_SLIP'))
    define( '_MANAGE_APA_TIKET_NUM_SLIP', 'Bordereau N&deg;');
if (!defined('_MANAGE_APA_TIKET_CARDBOARD'))
    define( '_MANAGE_APA_TIKET_CARDBOARD', 'Emplacement');
if (!defined('_MANAGE_APA_TIKET_UNIT'))
    define( '_MANAGE_APA_TIKET_UNIT', 'Unit&eacute;');
if (!defined('_MANAGE_APA_TIKET_TIME'))
    define( '_MANAGE_APA_TIKET_TIME', 'Heure');
if (!defined('_MANAGE_APA_TIKET_ADDRESS'))
    define( '_MANAGE_APA_TIKET_ADDRESS', 'Adresse');
if (!defined('_MANAGE_APA_TIKET_APPLICANT_NAME'))
    define( '_MANAGE_APA_TIKET_APPLICANT_NAME', 'Nom du demandeur');
if (!defined('_MANAGE_APA_TIKET_APPLICANT'))
    define( '_MANAGE_APA_TIKET_APPLICANT', 'Demandeur');
if (!defined('_MANAGE_APA_TIKET_FOR_SITE'))
    define( '_MANAGE_APA_TIKET_FOR_SITE', 'Pour dep&ocirc;t');
if (!defined('_MANAGE_APA_TIKET_FOR_SITE2'))
    define( '_MANAGE_APA_TIKET_FOR_SITE2', 'Pour depot');
if (!defined('_MANAGE_APA_TIKET_UA_LABELLE'))
    define( '_MANAGE_APA_TIKET_UA_LABELLE', 'Libell&eacute; Unit&eacute; Archive');
if (!defined('_MANAGE_APA_TIKET_COMMENT'))
    define( '_MANAGE_APA_TIKET_COMMENT', 'Commentaire');

if (!defined('_APA_PRINT_INVENTORY_LIST_CUSTOMER_NAT'))
    define( '_APA_PRINT_INVENTORY_LIST_CUSTOMER_NAT', 'Inventaire par client/nature/ann&eacute;e');
if (!defined('_APA_PRINT_INVENTORY_LIST_CUSTOMER_NAT_DESC'))
    define( '_APA_PRINT_INVENTORY_LIST_CUSTOMER_NAT_DESC', 'Inventaire par client/nature/ann&eacute;e');
if (!defined('_APA_PRINT_INVENTORY_LIST_CUSTOMER_UC_DESC'))
    define( '_APA_PRINT_INVENTORY_LIST_CUSTOMER_UC_DESC', 'Inventaire par client/UC');
if (!defined('_APA_PRINT_DOCKET_SEARCH_DESC'))
    define( '_APA_PRINT_DOCKET_SEARCH_DESC', 'Bordereaux de recherche');
if (!defined('_APA_PRINT_RETURN_BY_PERIOD_DESC'))
    define( '_APA_PRINT_RETURN_BY_PERIOD_DESC', 'R&eacute;int&eacute;grations pour une p&eacute;riode donn&eacute;e');
if (!defined('_APA_PRINT_OUT_NOT_RETURN_DESC'))
    define( '_APA_PRINT_OUT_NOT_RETURN_DESC', 'Sorties non r&eacute;int&eacute;gr&eacute;es');
if (!defined('_APA_PRINT_UC_DESTRUCTION'))
    define('_APA_PRINT_UC_DESTRUCTION', 'unit&eacute; de conditionnement pour destruction');
if (!defined('_APA_PRINT_UC_DESTRUCTION_DESC'))
    define( '_APA_PRINT_UC_DESTRUCTION_DESC', 'Proposition de destruction d&rsquo;UC');
if (!defined('_APA_PRINT_DESTRUCTION_PRINTING'))
    define('_APA_PRINT_DESTRUCTION_PRINTING', 'Liste de destruction');
if (!defined('_APA_PRINT_DESTRUCTION_PRINTING_DESC'))
    define( '_APA_PRINT_DESTRUCTION_PRINTING_DESC', 'Editions des &eacute;liminations');
if (!defined('_APA_PRINT_RESERVATION_BY_CUSTOMER_DATE'))
    define('_APA_PRINT_RESERVATION_BY_CUSTOMER_DATE', 'Nombre de r&eacute;servation par distributeur\client pour une p&eacute;riode donn&eacute;e');
if (!defined('_APA_PRINT_RESERVATION_BY_CUSTOMER_DATE_DESC'))
    define( '_APA_PRINT_RESERVATION_BY_CUSTOMER_DATE_DESC', 'Nombre de r&eacute;servations par concessionnaire/client sur une p&eacute;riode donn&eacute;e');
if (!defined('_APA_PRINT_RATIO_BY_SITE_BOX'))
    define('_APA_PRINT_RATIO_BY_SITE_BOX', 'Taux d\'occupation par distributor/site/row box');
if (!defined('_APA_PRINT_RATIO_BY_SITE_BOX_DESC'))
    define( '_APA_PRINT_RATIO_BY_SITE_BOX_DESC', 'Ratio de remplissage par concessionnaire/site/all&eacute;e ');
if (!defined('_APA_PRINT_IN_OUT_BY_CUSTOMER_DATE'))
    define('_APA_PRINT_IN_OUT_BY_CUSTOMER_DATE', 'Accessions and checkouts by site and distributor for a given period');
if (!defined('_APA_PRINT_IN_OUT_BY_CUSTOMER_DATE_DESC'))
    define( '_APA_PRINT_IN_OUT_BY_CUSTOMER_DATE_DESC', 'Entr&eacute;es et sorties par site sur une p&eacute;riode donn&eacute;e par concessionnaire');
if (!defined('_APA_PRINT_DOCKET_RESERVATION'))
    define('_APA_PRINT_DOCKET_RESERVATION', 'Reservation form');
if (!defined('_APA_PRINT_DOCKET_RESERVATION_DESC'))
    define( '_APA_PRINT_DOCKET_RESERVATION_DESC', 'Bordereaux de r&eacute;servation');

 /******************** DOUBLONS ******************************/
if(!defined('_NEW_SEARCH'))
    define( '_NEW_SEARCH', 'Effacer les crit&egrave;res');
if(!defined('_CUSTOMER'))
    define( '_CUSTOMER', 'Client');
if(!defined('_SEARCH_RESULTS'))
    define( '_SEARCH_RESULTS', 'R&eacute;sultat de la recherche');
if(!defined('_DETAILS'))
    define( '_DETAILS', 'Fiche d&eacute;taill&eacute;e');
if(!defined('_DETAILLED_PROPERTIES'))
    define( '_DETAILLED_PROPERTIES', 'Propri&eacute;t&eacute;s d&eacute;taill&eacute;es');

?>
