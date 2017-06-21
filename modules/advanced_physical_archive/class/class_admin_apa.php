<?php
/*
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

/**
*  Module  Advanced Physical Archive :  Administration of the advanced physical archives
*
* Forms and process to add, modify and delete sites, positions
*
* @file
* @author Yves Christian KPAKPO <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apa
* @brief   Module Advanced Physical Archive :  Administration of the advanced physical archives
*/

/**
*
* Forms and process to add, modify and delete sites, positions
*
* @ingroup apa
* @brief   Module Advanced Physical Archive :  M of the advanced physical archives
*/
class admin_apa extends apa_tools
{
    /**
    * Redefinition of the types object constructor : configure the sql argument order by
    */
    function __construct()
    {
        parent::__construct();
    }

    /**
    * This function get all entities in a select box (as a tree)
    *
    * @param string $entityId the root of the tree
    * @param string $parent nodes of the tree
    * @param string $selected identifier of the selected entity
    * @param string $tabspace margin of separation of tree's branches
    * @param array  $except array of entity_id ( elements of the tree that should not appear )
    */
    public function getEntityListBoxTree($entityId = '', $parent = '', $selected = '', $tabspace = '', $except = array(), $mode_str = false)
    {
        $this->connect();
        if($entityId <> '')
        {
            $this->query('select entity_id, entity_label from '.$_SESSION['tablename']['ent_entities']." where enabled = 'Y' and entity_id = '".$entityId."'");
        }
        elseif($parent <> '')
        {
            $this->query('select entity_id, entity_label from '.$_SESSION['tablename']['ent_entities']." where enabled = 'Y' and parent_entity_id = '".$parent."'");
        }
        if($this->nb_result() > 0)
        {
            $space = $tabspace.'&emsp;';
            while($line = $this->fetch_object())
            {
                if (!in_array($line->entity_id, $except))
                {
                    if($line->entity_id == $selected)
                    {
                        $str .= '<option value="'.$line->entity_id.'" selected="selected">'.$space.$this->show_string($line->entity_label).'</option>';
                    }
                    else
                    {
                        $str .= '<option value="'.$line->entity_id.'">'.$space.$this->show_string($line->entity_label).'</option>';
                    }
                    $db2 = new admin_apa();
                    $db2->connect();
                    $db2->query('select entity_id from '.$_SESSION['tablename']['ent_entities']." where enabled = 'Y' and parent_entity_id = '".$line->entity_id."'");
                    if($db2->nb_result() > 0)
                    {
                        $str .= $db2->getEntityListBoxTree('', $line->entity_id, $selected, $space, $except, true);
                    }
                }
            }
        }
        if($mode_str)
        {
            return $str;
        }
        else
        {
            echo $str;
        }
    }

    /**
    * form to add, update arvhive header
    *
    * @param $mode string mode of the operation
    */
    public function formapa($mode, $id='')
    {
        $func = new functions();
        $state = true;

        if($mode == "up")
        {
            $where ="";

            //Filter for entity
            $entitylist = $this->getentitylist();
            $where .= " and entity_id in(".$entitylist.")";

            //Get the header infos
            $this->connect();
            $this->query("select * from ".$_SESSION['tablename']['apa_header']." where header_id = '".$id."' ".$where);
            //$this->show();

            if($this->nb_result() == 0)
            {
                $_SESSION['error'] = _NO_ARCHIVE_CORRESPOND_TO_IDENTIFIER;
                $state = false;
            }
            else
            {
                $line = $this->fetch_object();

                if ($this->getarchivestatus($id,'CLO') || $this->getarchivestatus($id,'POS'))
                {
                    $_SESSION['apa']['header']['headerId'] = $line->header_id;
                    $_SESSION['apa']['header']['ctypeid'] = $line->ctype_id;
                    $_SESSION['apa']['header']['year1'] = $line->year_1;
                    $_SESSION['apa']['header']['year2'] = $line->year_2;
                    $_SESSION['apa']['header']['destructdate'] = $func->format_date_db($line->destruction_date, false);
                    $_SESSION['apa']['header']['allowdate'] = $func->format_date_db($line->allow_transmission_date, false);
                    $_SESSION['apa']['header']['ardesc'] = $func->show_string($line->header_desc);
                    $_SESSION['apa']['header']['entityid'] = $line->entity_id;
                    $_SESSION['apa']['header']['arnatureid'] = $line->arnature_id;
                    $_SESSION['apa']['header']['numua'] = $line->arbox_id;
                    $_SESSION['apa']['header']['numuc'] = $line->arcontainer_id;
                }
                else
                {
                    $_SESSION['error'] = _WARNING_UPDATE_LOCKED_ARCHIVE;;
                    $state = false;
                }
            }
        }
            if (!isset($_SESSION['apa']['header']['year1']) || empty($_SESSION['apa']['header']['year1']))
            {
                $_SESSION['apa']['header']['year1'] = date('Y');
            }

            if (!isset($_SESSION['apa']['header']['year2']) || empty($_SESSION['apa']['header']['year2']))
            {
                $_SESSION['apa']['header']['year2'] = date('Y');
            }

            if($mode == "add")
            {
                echo '<h1><img src="'.$_SESSION['config']['businessappurl'].'static.php?module=advanced_physical_archive&filename=add_archive_b.gif" alt="" /> '._MANAGE_APA_ADD_HEADER.'</h1>';
            }
            elseif($mode == "up")
            {
                echo '<h1><img src="'.$_SESSION['config']['businessappurl'].'static.php?module=advanced_physical_archive&filename=up_archive_b.gif" alt="" /> '._UPDATE_ARCHIVE.' '.strtolower(_NUM).$id.'</h1>';
            }

            if($mode == "up")
            {
                $link = $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=apa_up_db";
            }
            elseif($mode == "add")
            {
                $link = $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=apa_add_db";
            }
            ?>
            <script type="text/javascript">
            function GetXmlHttpObject()
            {
                var req = null;

                if (window.XMLHttpRequest)
                {
                    req = new XMLHttpRequest();
                }
                else if (window.ActiveXObject)
                {
                    try {
                        req = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e)
                    {
                        try {
                            req = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {}
                    }
                }
                return req;
            }

            function updateListBox(ID, Param, what, text)
            {

                //link to the PHP file your getting the data from
                var loaderphp = null;

                if (what =="nature")
                {
                    var loaderphp = "<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=nature_dropdown_loader";?>";
                }
                else if (what =="position")
                {
                    var loaderphp = "<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=position_dropdown_loader";?>";
                }

                xmlHttp = GetXmlHttpObject();

                xmlHttp.onreadystatechange=function()
                {
                    if (xmlHttp.readyState == 1)
                    {
                        document.getElementById(ID).innerHTML = "Loading...";
                    }

                    if(xmlHttp.readyState==4)
                    {
                        //This set the dat from the php to the html
                        if (text == null)
                        {
                            document.getElementById(ID).innerHTML = xmlHttp.responseText;
                        }
                        else
                        {   //Text field
                            document.getElementById(ID).value = xmlHttp.responseText;
                        }
                    }
                }
                //alert(loaderphp+"?id="+ID+"&value="+Param);
                xmlHttp.open("GET", loaderphp+"&id="+ID+"&value="+Param,true);
                xmlHttp.send(null);
            }

            function updateTextBox(ID, Param, textbox)
            {
                //link to the PHP file your getting the data from
                var loaderphp = "<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=nature_dropdown_loader";?>";

                xmlHttp = GetXmlHttpObject();

                xmlHttp.onreadystatechange=function()
                {
                    if (xmlHttp.readyState == 1)
                    {
                        document.getElementById(textbox).value = "Loading...";
                    }

                    if(xmlHttp.readyState==4)
                    {
                        document.getElementById(textbox).value = xmlHttp.responseText;
                    }
                }
                //alert(loaderphp+"?id="+ID+"&value="+Param+"&textbox="+textbox);
                xmlHttp.open("GET", loaderphp+"&id="+ID+"&value="+Param+"&textbox="+textbox,true);
                xmlHttp.send(null);
            }
            </script>
            <div id="inner_content" class="clearfix">
            <?php
           if($mode == "up")
           {
                ?>
                <div class="block">
                <b>
                    <p id="back_list">
                        <a href="#" onclick="history.go(-1);" class="back"><?php echo _BACK; ?></a>
                    </p>
                </b>&nbsp;
                </div>
                <br/>
                <?php
            }

            if($state == false)
            {
                echo "<br /><br /><br /><br />".$_SESSION['error']."<br /><br /><br /><br />";
            }
            else
            {
            ?>
                <form name="formapa" id="formapa" method="post" action="<?php echo $link; ?>" class="forms">
                    <input type="hidden"  name="display" value="true" />
                    <input type="hidden"  name="module" value="advanced_physical_archive" />
                <?php   if($mode == "up")
                {?>
                    <input type="hidden"  name="page" value="apa_ub_db" />
            <?php }
                elseif($mode == "add")
                {?>
                <input type="hidden"  name="page" value="apa_add_db" />
            <?php   }?>
                    <input type="hidden"  name="siteid" value="<?php echo $siteid; ?>" />
                    <table width="90%" border="0">
                        <tr>
                        <?php
                        $_SESSION['apa']['containertypeslist'] = $this->getcontainertypes();
                        //$func->show_array($_SESSION['apa']['containertypeslist']);
                        ?>
                            <td width="25%" align="right"><?php echo _THE_CONTAINER_TYPES;?> :</td>
                            <td width="24%"nowrap>
                                <select name="ctypeid" id="ctypeid" class="selectform">
                                    <option value=""><?php echo _CHOOSE_CONTAINER_TYPES;?></option>
                                <?php
                                for($i=0;$i<count($_SESSION['apa']['containertypeslist']);$i++)
                                {
                                    if($_SESSION['apa']['containertypeslist'][$i]['id'] == $_SESSION['apa']['header']['ctypeid'])
                                    {
                                        ?>
                                            <option value="<?php echo $_SESSION['apa']['containertypeslist'][$i]['id'];?>" selected="selected"><?php echo $_SESSION['apa']['containertypeslist'][$i]['desc'];?></option>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <option value="<?php echo $_SESSION['apa']['containertypeslist'][$i]['id'];?>"><?php echo $_SESSION['apa']['containertypeslist'][$i]['desc'];?></option>
                                        <?php
                                    }
                                }
                                ?>
                                </select>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td width="25%" align="right">&nbsp;</td>
                            <td width="24%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_CUSTOMER;?> :</td>
                            <td width="24%" nowrap>
                                <select name="entityid" id="entityid" onchange="updateListBox('naturebox', this.value, 'nature');" class="selectform">
                                    <option value=""><?php echo _MANAGE_APA_CHOOSE_CUSTOMER;?></option>
                                    <?php
                                    for ($j=0; $j < count($_SESSION['user']['entities']); $j++)
                                    {
                                        /*
                                        if($_SESSION['user']['entities'][$j]['ENTITY_ID'] == $_SESSION['apa']['header']['entityid'])
                                        {
                                            ?>
                                                <option value="<?php echo $_SESSION['user']['entities'][$j]['ENTITY_ID'];?>" selected="selected"><?php echo $_SESSION['user']['entities'][$j]['ENTITY_LABEL'];?></option>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <option value="<?php echo $_SESSION['user']['entities'][$j]['ENTITY_ID'];?>"><?php echo $_SESSION['user']['entities'][$j]['ENTITY_LABEL'];?></option>
                                            <?php
                                        }
                                        */
                                        $this->getEntityListBoxTree($_SESSION['user']['entities'][$j]['ENTITY_ID'], '', $_SESSION['apa']['header']['entityid'], '' , $except = array());
                                        //$this->getEntityListBoxTree('', $_SESSION['apa']['header']['entityid'], '' , $except = array());
                                    }
                                    ?>
                                </select><span class="red_asterisk">*</span>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td width="25%" align="right"><?php echo _TYPE;?> :</td>
                            <td width="24%" nowrap>
                            <?php
                            $this->connect();
                            $this->query("select arnature_id, arnature_desc
                                        from ".$_SESSION['tablename']['apa_natures']."
                                        where entity_id = '".$_SESSION['apa']['header']['entityid']."'
                                        order by arnature_desc");
                            ?>
                                <span id="naturebox">
                                    <select name="arnatureid" id="arnatureid" onchange="updateListBox('destructdate', this.value, 'nature', true);" class="selectform">
                                        <option value=""><?php echo _CHOOSE_NATURE;?></option>
                                    <?php
                                        while($line = $this->fetch_object())
                                        {
                                            if($line->arnature_id == $func->show_str($_SESSION['apa']['header']['arnatureid']))
                                            {
                                                ?>
                                                    <option value="<?php echo $line->arnature_id ;?>" selected="selected"><?php echo $line->arnature_desc ;?></option>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <option value="<?php echo $line->arnature_id ;?>"><?php echo $line->arnature_desc ;?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                    </select><span class="red_asterisk">*</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_DATE1;?> :</td>
                            <td width="24%" nowrap>
                                <input type="text" name="year1" id="year1" class="medium2" value="<?php echo $_SESSION['apa']['header']['year1'] ;?>" onclick='showCalender(this)' />
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_DATE2;?> :</td>
                            <td width="24%" nowrap>
                                <input type="text" name="year2" id="year2" onchange="updateTextBox('destructdate2', this.value, 'destructdate');" onclick='showCalender(this)' class="medium2" value="<?php echo $_SESSION['apa']['header']['year2'] ;?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_NUM_UA;?> :</td>
                            <td width="24%" nowrap>
                            <?php
                            if($mode == "add")
                            {
                                ?>
                                <input name="numua" type="text" id="numua" class="medium2" value="<?php echo $_SESSION['apa']['header']['numua'] ;?>" />
                                <?php
                            }
                            elseif($mode == "up")
                            {
                                ?>
                                <input name="numua" type="text" id="numua" class="medium2 readonly" readonly="readonly" value="<?php echo $_SESSION['apa']['header']['numua'] ;?>" />
                                <?php
                            }
                            ?>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_NUM_UC;?> :</td>
                            <td width="24%" nowrap>
                                <input name="numuc" type="text" id="numuc" class="medium2" value="<?php echo $_SESSION['apa']['header']['numuc'] ;?>" />
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_DESTRUCTION_DATE;?> :</td>
                            <td width="24%" nowrap>
                                <input name="destructdate" type="text" id="destructdate"  class="medium2" value="<?php echo $_SESSION['apa']['header']['destructdate'] ;?>" onclick='showCalender(this)'/>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_ALLOW_DATE;?> :</td>
                            <td width="24%" nowrap>
                                <input name="allowdate" type="text" id="allowdate"  class="medium2" value="<?php echo $_SESSION['apa']['header']['allowdate'] ;?>" onclick='showCalender(this)'/>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_HEADER_DESC;?> :</td>
                            <td colspan="4">
                                <textarea  cols="30" rows="2"  name="ardesc"  id="ardesc" ><?php echo $func->show_str($_SESSION['apa']['header']['ardesc']); ?></textarea><span class="red_asterisk">*</span>
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <p class="buttons">
                        <input class="button" name="submit" type="submit" value="<?php echo _VALIDATE; ?>"/>
                        <?php
                        if($mode == "add")
                        {
                            ?>
                            <input type="button" name="cancel" value="<?php echo _CANCEL; ?>" class="button"  onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=manage_apa&amp;module=advanced_physical_archive';"/>
                            <?php
                        }
                        elseif($mode == "up")
                        {
                            ?>
                            <input type="button" name="cancel" value="<?php echo _CANCEL; ?>" class="button"  onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=list_apa&amp;module=advanced_physical_archive';"/>
                            <?php
                        }
                        ?>
                    </p>
                    <br>
                </form>
                <?php
            }
            ?>
            </div>
        <?php
    }

    /**
    * form to add, update arvhive header from file
    *
    * @param $mode string mode of the operation
    */
    public function formapareturn()
    {
        echo '<h1><img src="'.$_SESSION['config']['businessappurl'].'static.php?module=advanced_physical_archive&filename=return_archive_b.gif" alt="" /> '._MANAGE_APA_AUTO_RETURN.'</h1>';
        ?>
        <div id="inner_content" class="clearfix">
            <div class="block"><br/>
                <form name="form_return" id="form_return" method="post" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=advanced_physical_archive&page=apa_return_db" class="forms">
                <input type="hidden" name="display" value="true" />
                <input type="hidden" name="module" value="advanced_physical_archive" />
                <input type="hidden" name="page" value="apa_return_db" />
                <label for="file"><?php echo _NUM_ARCHIVE; ?> : </label>
                <input name="apaid" type="text" id="apaid" class="medium2" value="<?php echo $_SESSION['apa']['return']['id'] ;?>" />
                <br/><br/>
                <p class="buttons">
                    <input class="button" name="submit" type="submit" value="<?php echo _VALIDATE; ?>"/>
                    <input type="button" name="cancel" value="<?php echo _CANCEL; ?>" class="button"  onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=manage_apa&amp;module=advanced_physical_archive';"/>
                </p>
               </form>
           </div>
            <div class="block_end">&nbsp;</div>
        </div>
        <?php
    }

    /**
    * form to add, update UC
    *
    * @param $mode string mode of the operation
    */
    public function formuc($mode)
    {
        $func = new functions();
        $core_tools = new core_tools();
        $checked_many = '';
        $checked_one = '';
        echo '<h1><img src="'.$_SESSION['config']['businessappurl'].'static.php?module=advanced_physical_archive&filename=add_uc_b.gif" alt="" /> '._MANAGE_APA_ADD_UC.'</h1>';
        echo '<br/>';
		echo $core_tools->execute_modules_services($_SESSION['modules_services'], 'class_admin_apa.php', "frame", "choose_site", "advanced_physical_archive");

        if($mode == "up")
         {
             $link = $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=uc_up_db";
            $page= "uc_up_db";
        }
        elseif($mode == "add")
        {
            $link = $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=uc_add_db";
            $page= "uc_add_db";
        }
        elseif($mode == "del")
        {
            $link =  $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=uc_del_db";
            $page= "uc_del_db";
        }
        ?>
        <script type="text/javascript">
        function GetXmlHttpObject()
        {
            var req = null;

            if (window.XMLHttpRequest)
            {
                req = new XMLHttpRequest();
            }
            else if (window.ActiveXObject)
            {
                try {
                    req = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e)
                {
                    try {
                        req = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {}
                }
            }
            return req;
        }

        function updateListBox(ID, Param)
        {
             //link to the PHP file your getting the data from
            //window.alert('test1');

            var loaderphp = "<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=position_capacity_dropdown_loader";?>";

            xmlHttp = GetXmlHttpObject();

            xmlHttp.onreadystatechange=function()
            {
                if (xmlHttp.readyState == 1)
                {
                    document.getElementById(ID).innerHTML = "Loading...";
                }

                if(xmlHttp.readyState==4)
                {
                    //Theses lines below reset the third list box and the total field incase row list is changed
                    if(ID =="colbox")
                    {
                        //alert(ID);
                        var resetLevelBox = '<select name="level" id="level" class="small"></select>';
                        document.getElementById('levelbox').innerHTML = resetLevelBox ;
                        document.getElementById('capacity').innerHTML = "";
                    }
                    //This set the dat from the php to the html
                    document.getElementById(ID).innerHTML = xmlHttp.responseText;
                }
            }
            //alert(loaderphp+"&id="+ID+"&value="+Param);
            xmlHttp.open("GET", loaderphp+"&id="+ID+"&value="+Param,true);
            xmlHttp.send(null);
        }
        </script>
        <div id="inner_content" class="clearfix">
            <form name="formuc" id="formuc" method="post" action="<?php  echo $link; ?>" class="forms addforms">
                <input type="hidden" name="display" value="true" />
                <input type="hidden" name="module" value="advanced_physical_archive" />
                <input type="hidden" name="page" value="<?php echo $page;?>" />
                <p>
                <?php
                    //echo $_SESSION['apa']['site'];
                    // Manage the radio button state
                    switch ($_SESSION['apa']['positions']['ucs'])
                    {
                        case "one" :
                            $checked_one = 'checked="checked"';
                        break;

                        case "many" :
                            $checked_many = 'checked="checked"';
                        break;

                        default:
                        $checked_one = 'checked="checked"';

                    }

                    ?>
                    <label><input type="radio" name="ucs"  id="oneuc" value="one" <?php echo $checked_one;?> />
                    <?php echo _MANAGE_APA_NUM_UC_SHORT;?> : </label>
                    <input name="uc" id="uc" type="text" class="medium2" value="<?php echo $func->show_str($_SESSION['apa']['positions']['uc']); ?>" onFocus="document.getElementById('oneuc').checked=true;document.getElementById('fromuc').value='';document.getElementById('touc').value='';" /><span class="red_asterisk">*</span>
                     <div id="show_uc" class="autocomplete"></div>
                </p>
                <?php echo "<p>"._OR."</p>";?>
                <p>
                    <label><input type="radio" name="ucs"  id="manyuc" value="many" <?php echo $checked_many;?>/>
					<?php echo _MANAGE_APA_FROM_UC;?> : </label>
                    <input name="fromuc" id="fromuc" type="text" class="medium2" value="<?php echo $func->show_str($_SESSION['apa']['positions']['fromuc']); ?>" onFocus="document.getElementById('manyuc').checked=true;document.getElementById('uc').value='';" /><span class="red_asterisk">*</span>
                    <div id="show_fromuc" class="autocomplete"></div>

                </p>
                <p>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php echo _MANAGE_APA_TO_UC;?> : </label>
                    <input name="touc" id="touc" type="text" class="medium2" value="<?php echo $func->show_str($_SESSION['apa']['positions']['touc']); ?>" onFocus="document.getElementById('manyuc').checked=true;document.getElementById('uc').value='';" />
                    <div id="show_touc" class="autocomplete"></div>

                </p>
                <script type="text/javascript">
                    initList('uc', 'show_uc', '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=advanced_physical_archive&page=autocomplete_uc', 'input', '1');
                    initList('fromuc', 'show_fromuc', '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=advanced_physical_archive&page=autocomplete_uc', 'input', '1');
                    initList('touc', 'show_touc', '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=advanced_physical_archive&page=autocomplete_uc', 'input', '1');
				</script>
                <p>
                    <label><?php echo _POSITIONS_ROW;?></label>
                    <span id="rowbox">
                        <select name="row" id="row" onchange="updateListBox('colbox', this.value);" class="small">
                            <option value=""><?php echo _POSITIONS_CHOOSE_ROW;?></option>
                            <?php
                            $db = new dbquery();
                            $db->connect();
                            //$db->query("select distinct(pos_row) as row from ".$_SESSION['tablename']['apa_positions']." where site_id = '".$_SESSION['apa']['site']."' group by pos_row");
                            $db->query("SELECT DISTINCT(pos_row) as row FROM ".$_SESSION['tablename']['apa_positions']." WHERE site_id = '".$_SESSION['apa']['site']."' ORDER BY pos_row ASC");
                            if($db->nb_result() > 0)
                            {
                                while($line = $db->fetch_object())
                                {
                                    ?>
                                        <option value="<?php echo $line->row ;?>"><?php echo $line->row ;?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </span>
                </p>
                <p>
                    <label><?php echo _POSITIONS_COL;?> :</label>
                    <span id="colbox">
                        <select name="col" id="col" onchange="updateListBox('levelbox', this.value);" class="small">
                            <option value=""><?php echo _POSITIONS_CHOOSE_COL;?></option>
                        </select>
                    </span>
                </p>
                <p>
                    <label><?php echo _POSITIONS_LEVEL;?> : </label>
                    <span id="levelbox">
                        <select name="level" id="level" onchange="updateListBox('capacity', this.value);" class="small">
                            <option value=""><?php echo _POSITIONS_CHOOSE_LEVEL;?></option>
                        </select>
                    </span>
                </p>
                <p>
                    <label><?php echo _POSITIONS_AVAILABLE_UC;?> : </label>
                    <label name="capacity" id="capacity" class="readonly" style="width:55px;"></label>
                </p>

                <p class="buttons">
                    <input type="submit" name="Submit" value="<?php echo _VALIDATE; ?>" class="button" />
                    <input type="button" name="cancel" value="<?php echo _CANCEL; ?>" class="button"  onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=manage_apa&amp;module=advanced_physical_archive';"/>
                </p>
            </form>
        </div>
        <?php
    }

    /**
    * Treats the information returned by the form of formuc()
    *
    * @param $mode administrator string mode (modification, delete)
    */
    private function ucinfo($mode)
    {
        $func = new functions();
        //$func->show_array($_POST);

        $_SESSION['apa']['positions']['site'] = $func->wash($_SESSION['apa']['site'], "alphanum", _THE_SITE);

        if(isset($_POST['ucs']) && !empty($_POST['ucs']))
        {
            if ($_POST['ucs'] =="one")
            {
                if(isset($_POST['uc']))
                {
                    $_SESSION['apa']['positions']['uc'] = $func->wash($_POST['uc'], "num", _MANAGE_APA_NUM_UC_SHORT);

                    //Clear UC
                    $_SESSION['apa']['positions']['fromuc'] = "";
                    $_SESSION['apa']['positions']['touc'] = "";
                }
                else
                {
                    $_SESSION['error'] .= _MANAGE_APA_NUM_UC_SHORT." "._MANDATORY."<br>";
                }
            }
            elseif($_POST['ucs'] =="many")
            {
                if(isset($_POST['fromuc']))
                {
                    $_SESSION['apa']['positions']['fromuc'] = $func->wash($_POST['fromuc'], "num", _MANAGE_APA_UC_BEGIN);

                    //Clear UC
                    $_SESSION['apa']['positions']['uc'] = "";
                }
                else
                {
                    $_SESSION['error'] .= _MANAGE_APA_UC_BEGIN." "._MANDATORY."<br>";
                }

                if(isset($_POST['touc']) && !empty($_POST['touc']))
                {
                    $_SESSION['apa']['positions']['touc'] = $func->wash($_POST['touc'], "num", _MANAGE_APA_UC_END);
                }
                else
                {
                    $_SESSION['error'] .= _MANAGE_APA_UC_END." "._MANDATORY."<br>";
                }
            }

            $_SESSION['apa']['positions']['ucs'] = $_POST['ucs'];
        }
        else
        {
            $_SESSION['error'] .= _MANAGE_APA_NUM_UC." "._MANDATORY."<br>";
        }

        if(isset($_POST['row']))
        {
            $_SESSION['apa']['positions']['row'] = $func->wash($_POST['row'], "alphanum", _POSITIONS_ROW);
        }
        else
        {
            $_SESSION['error'] .= _POSITIONS_ROW." "._MANDATORY."<br>";
        }

        if(isset($_POST['col']) && !empty($_POST['col']))
        {
            $_SESSION['apa']['positions']['col'] = $func->wash($_POST['col'], "num", _POSITIONS_COL);
        }
        else
        {
            $_SESSION['error'] .= _POSITIONS_COL." "._MANDATORY."<br>";
        }

        if(isset($_POST['level']) && strlen($_POST['level'])>0)
        {
            $_SESSION['apa']['positions']['level'] = $func->wash($_POST['level'], "num", _POSITIONS_LEVEL);
        }
        else
        {
            $_SESSION['error'] .= _POSITIONS_LEVEL." "._MANDATORY."<br>";
        }

        //echo $_SESSION['error'];
        //$func->show_array($_SESSION['apa']['positions']);exit;
    }

    /**
    * Clean the $_SESSION['apa']['positions'] array
    */
    private function clearucinfos()
    {
        // clear the containers add or modification vars
        $_SESSION['apa']['positions'] = array();
        $_SESSION['apa']['positions']['site'] = "";
        $_SESSION['apa']['positions']['row'] = "";
        $_SESSION['apa']['positions']['uc'] = "";
        $_SESSION['apa']['positions']['ucs'] = "";
        $_SESSION['apa']['positions']['fromuc'] = "";
        $_SESSION['apa']['positions']['touc'] = "";
        $_SESSION['apa']['positions']['col'] = "";
        $_SESSION['apa']['positions']['level'] = "";
    }
    /**
    * Add ou modify container position in the database
    *
    * @param $mode string up or add
    */
    public function addupuc($mode, $cancel)
    {
        $this->ucinfo($mode);

        $func = new functions();

        if(!empty($_SESSION['error']) && empty($cancel))
        {
            header("location: ".$_SESSION['config']['businessappurl']."index.php?page=uc_add&module=advanced_physical_archive");
            exit;
        }
        else
        {
            $this->connect();

            $ctype_id = 0;
            $uc = array();
            $alert = false;
            $success = false;
            $_SESSION['error'] = "";

            $site = $_SESSION['apa']['positions']['site'];
            $row = strtoupper($_SESSION['apa']['positions']['row']);
            $col = $_SESSION['apa']['positions']['col'];
            $level = $_SESSION['apa']['positions']['level'];
            $entity = $_SESSION['user']['primaryentity']['id'];

            //Only one UC to add
            if (isset($_SESSION['apa']['positions']['uc']) && !empty($_SESSION['apa']['positions']['uc']))
            {
                $uc[] = $_SESSION['apa']['positions']['uc'];
            }//Many UC to add
            elseif (isset($_SESSION['apa']['positions']['fromuc']) && !empty($_SESSION['apa']['positions']['fromuc']))
            {
                for ($i=$_SESSION['apa']['positions']['fromuc']; $i<=$_SESSION['apa']['positions']['touc']; $i++)
                {
                    $uc[] = $i;
                }
            }

            for ($i=0; $i<count($uc); $i++)
            {
                //If the container exist
                if($this->checkarcontainer($uc[$i], $entity))
                {
                    //Do the current user have accees to this container
                    if ($this->accessarcontainer($uc[$i]))
                    {
                        //Is the UC is used
                        if( $this->useduc($uc[$i]))
                        {
                            //Is the position exist
                            if (!$this->positionisavailable ($site,$row,$col,$level))
                            {
                                //Get the id of position
                                $positionid = $this->getpositionid($site,$row,$col,$level);

                                //Avalaible capacity
                                $ucnumber = $this->getpositionavailablecapacity($positionid);

                                //Is there enought space for the UC
                                if ($ucnumber >0)
                                {
                                    //Get the actual position of the container
                                    $container_moved = false;
                                    $container_positioned = false;
                                    $container_position = array();
                                    $container_position = $this->containerposition($uc[$i], $entity);

                                    //If the container is already positioned
                                    if(count($container_position) >0)
                                    {
                                        $container_positioned = true;

                                        //Check if the position change
                                        $container_old_position = $container_position['position'];

                                        if($container_old_position != $positionid )
                                        {
                                            $container_moved = true;
                                        }
                                    }

                                    //If the container is already positioned
                                    if ($container_positioned && empty($cancel))
                                    {
                                        ?>
                                        <script type="text/javascript">
                                            var isComfirm = confirm('<?php echo addslashes(html_entity_decode(_MANAGE_APA_UC_ALREADY_POSITIONED,  ENT_COMPAT, 'UTF-8'))."\\n\\n "._MANAGE_APA_UC_ALREADY_POSITIONED_CONTINUE; ?>');

                                            if (isComfirm == false)
                                            {
                                                window.top.location.href='<?php echo $_SESSION['config']['businessappurl']."index.php?page=manage_apa&module=advanced_physical_archive";?>';
                                            }
                                            else if (isComfirm == true)
                                            {
                                                window.top.location.href='<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=uc_add_db&cancel=false";?>';
                                            }
                                        </script>
                                        <?php

                                    }else
                                    {   //Else whe put it in the position
                                        $cancel = "false";
                                    }


                                    if ($cancel == "false")
                                    {
                                        //Update the position of container
                                        $this->query("update ".$_SESSION['tablename']['apa_containers']." set position_id = ".$positionid.", status = 'POS' where arcontainer_id = '".$uc[$i]."' and entity_id = '".$entity."'");

                                        //get all the box in the uc
                                        $tab_box = array();

                                        $tab_box = $this->getallbox($uc[$i]);
                                        //$func->show_array($tab_box);

                                        if(count($tab_box)<1){
											$_SESSION['error'] = 'L\'utilisateur doit appartenir a l\'entite parente de l\'entite versante dans l\'archive ';
											$alert = true;
										}
										else{
                                        for($ii=0; $ii<count($tab_box); $ii++)
                                        {
                                            //Update the arbox status only if the archiv is new
                                            $this->query("update ".$_SESSION['tablename']['apa_boxes']." set status = 'POS' where arbox_id = '".$tab_box[$ii]['arbox_id']."' and status = 'CLO'");
                                        }

                                        //Update the arbox status only if the archiv is new
                                        //$this->query("update ".$_SESSION['tablename']['apa_boxes']." set status = 'POS' where ( arcontainer_id = '".$uc[$i]."' and entity_id = '".$entity."' ) and status = 'NEW'");

                                        //Update the number of available UC positions if the container is not positioned yet or if the container is moved
                                        if((!$container_positioned) || ($container_positioned && $container_moved))
                                        {
                                            $this->query("update ".$_SESSION['tablename']['apa_positions']." set pos_available_uc = (pos_available_uc -1) where position_id = ".$positionid);
                                        }

                                        //Update the number of available UC positions if the container is moved
                                        if($container_moved)
                                        {
                                            $this->query("update ".$_SESSION['tablename']['apa_positions']." set pos_available_uc = (pos_available_uc +1) where position_id = ".$container_old_position);
                                        }

                                        //Update the site id in header table
                                        $siteid = $this->getpositionsite($positionid);
                                        for($ii=0; $ii<count($tab_box); $ii++)
                                        {
                                            $this->query("update ".$_SESSION['tablename']['apa_header']." set site_id = '".$siteid."' where header_id = '".$tab_box[$ii]['header_id']."'");
                                        }
                                        //History
                                        if($_SESSION['history']['archiveloc'] == "true")
                                        {
                                            require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                                            $hist = new history();

                                            $this->query("select header_id from ".$_SESSION['tablename']['apa_header']." where arcontainer_id = '".$uc[$i]."' and entity_id = '".$entity."'");

                                            while($line = $this->fetch_object())
                                            {
                                                $hist->add($_SESSION['tablename']['apa_header'], $line->header_id ,"POS", 'archiveloc', _MANAGE_APA_UC_POSITIONED.": ".$row."/".$col."/".$level, $_SESSION['config']['databasetype'], 'advanced_physical_archive');
                                            }

                                            $hist->add($_SESSION['tablename']['apa_containers'], $uc[$i] ,"POS",  'archiveloc', _MANAGE_APA_UC_POSITIONED.": ".$row."/".$col."/".$level, $_SESSION['config']['databasetype'], 'advanced_physical_archive');
                                        }

                                        $success = true;
                                        $_SESSION['error'] .= $uc[$i].": "._MANAGE_APA_UC_POSITIONED."<br/>";
										}
                                    }
                                    else
                                    {
                                        $_SESSION['error'] .= $uc[$i].": "._MANAGE_APA_UC_POSITION_CANCELED."<br/>";
                                    }
                                }
                                else
                                {
                                    $_SESSION['error'] = $uc[$i].": "._POSITIONS_IS_FULL."<br/>";
                                    $_SESSION['error'] .= _POSITIONS_CHOOSE_ANOTHER;
                                    $alert = true;
                                }
                            }
                            else
                            {
                                $_SESSION['error'] = _POSITIONS_DONT_EXIST."<br/>";
                                $_SESSION['error'] .= _POSITIONS_MUST_BE_CREATE;
                                $alert = true;
                            }

                        }
                        else
                        {
                            $_SESSION['error'] = $uc[$i].": "._MANAGE_APA_UC_EMPTY."<br/>";
                            $alert = true;
                        }
                    }
                    else
                    {
                        $_SESSION['error'] = $uc[$i].": "._MANAGE_APA_UC_NO_ACCESS."<br/>";
                        $alert = true;
                    }
                }
                else
                {
                    $_SESSION['error'] = $uc[$i].": "._MANAGE_APA_UC_DOES_NOT_EXISTS."<br/>";
                    $alert = true;
                }

                //Go back to the uc add form
                if($alert)
                {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=uc_add&module=advanced_physical_archive");
                    exit;
                }

            }

            if ($success)
            {
                //Go back to the management screen
                $this->clearucinfos();
                header("location: ".$_SESSION['config']['businessappurl']."index.php?page=manage_apa&module=advanced_physical_archive");
                exit;
            }
        }
    }
    /**
    * Treats the information returned by the form of formapa()
    *
    * @param $mode administrator string mode
    */
    private function apainfo($mode)
    {
        $func = new functions();
        //$func->show_array($_POST);

        //Container type
        if(isset($_POST['ctypeid']) && !empty($_POST['ctypeid']))
        {
                $_SESSION['apa']['header']['ctypeid'] = $func->wash($_POST['ctypeid'], "alphanum", _THE_CONTAINER_TYPES);
        }

        //Customer (Entity)
        if(isset($_POST['entityid']) && !empty($_POST['entityid']))
        {
                $_SESSION['apa']['header']['entityid'] = $func->wash($_POST['entityid'], "alphanum", _MANAGE_APA_CUSTOMER);
        }
        else
        {
            $_SESSION['error'] .= _MANAGE_APA_CUSTOMER." "._MANDATORY."<br>";
        }
        //Nature
        if(isset($_POST['arnatureid']) && !empty($_POST['arnatureid']))
        {
                $_SESSION['apa']['header']['arnatureid'] = $func->wash($_POST['arnatureid'], "alphanum", _NATURE);
        }
        else
        {
            $_SESSION['error'] .= _NATURE." "._MANDATORY."<br>";
        }
        //Year 1
        if(isset($_POST['year1']) && !empty($_POST['year1']))
        {
                $_SESSION['apa']['header']['year1'] = $func->wash($_POST['year1'], "num", _MANAGE_APA_YEAR1);
        }

        //Year 2
        if(isset($_POST['year2']) && !empty($_POST['year2']))
        {
                $_SESSION['apa']['header']['year2'] = $func->wash($_POST['year2'], "num", _MANAGE_APA_YEAR2);
        }

        //UC Identifier
        if(isset($_POST['numuc']) && !empty($_POST['numuc']))
        {
            if (is_numeric($_POST['numuc'])) {
                $_SESSION['apa']['header']['numuc'] = $func->wash($_POST['numuc'], "num", _MANAGE_APA_NUM_UC);
            } else {
                $_SESSION['error'] .= _MANAGE_APA_NUM_UC." doit etre numerique<br>";
            }
                
        }

        //UA Identifier
        if(isset($_POST['numua']) && !empty($_POST['numua']))
        {
                $_SESSION['apa']['header']['numua'] = $func->wash($_POST['numua'], "num", _MANAGE_APA_NUM_UA);
        }

        //Destruction date
        if(isset($_POST['destructdate']) && !empty($_POST['destructdate']))
        {
                $_SESSION['apa']['header']['destructdate'] = $func->wash($_POST['destructdate'], "date", _MANAGE_APA_DESTRUCTION_DATE);
        }

        //Allow date
        if(isset($_POST['allowdate']) && !empty($_POST['allowdate']))
        {
                $_SESSION['apa']['header']['allowdate'] = $func->wash($_POST['allowdate'], "date", _MANAGE_APA_ALLOW_DATE);
        }

        //Header description
        if(isset($_POST['ardesc']) && !empty($_POST['ardesc']))
        {
                $_SESSION['apa']['header']['ardesc'] = $func->wash($_POST['ardesc'], "no", _MANAGE_APA_HEADER_DESC);
        }
        else
        {
            $_SESSION['error'] .= _MANAGE_APA_HEADER_DESC." "._MANDATORY."<br>";
        }

        //echo $_SESSION['error'];
        //$func->show_array($_SESSION['apa']['header']);exit;
    }

    /**
    * Clean the $_SESSION['apa']['header'] array
    */
    private function clearapainfos()
    {
        // clear the containers add or modification vars
        $_SESSION['apa']['header'] = array();
        $_SESSION['apa']['header']['headerId'] = "";
        $_SESSION['apa']['header']['ctypeid'] = "";
        $_SESSION['apa']['header']['entityid'] = "";
        $_SESSION['apa']['header']['arnatureid'] = "";
        $_SESSION['apa']['header']['year1'] = "";
        $_SESSION['apa']['header']['year2'] = "";
        $_SESSION['apa']['header']['numuc'] = "";
        $_SESSION['apa']['header']['numua'] = "";
        $_SESSION['apa']['header']['destructdate'] = "";
        $_SESSION['apa']['header']['allowdate'] = "";
        $_SESSION['apa']['header']['ardesc'] = "";
    }

    /**
    * Clean the $_SESSION['apa']['import'] array
    */
    private function clearapaimportinfos()
    {
        // clear the import informations
        $_SESSION['apa']['import']['ctypeid'] = '';
        $_SESSION['apa']['import']['year1'] = '';
        $_SESSION['apa']['import']['year2'] = '';
        $_SESSION['apa']['import']['numua'] = '';
        $_SESSION['apa']['import']['numuc'] = '';
        $_SESSION['apa']['import']['cusEntityId'] = '';
        $_SESSION['apa']['import']['arnatureid'] = '';
        $_SESSION['apa']['import']['destructdate'] = '';
        $_SESSION['apa']['import']['ardesc'] = '';
        $_SESSION['apa']['import']['allowdate'] = '';

    }

    /**
    * Add ou modify archive in the database
    *
    * @param $mode string up or add
    */
    public function addupapa($mode, $cancel="")
    {

        if(empty($cancel))
        {
            $this->apainfo($mode);
        }


        if(!empty($_SESSION['error']) && empty($cancel))
        {
            if($mode == "add")
            {
                header("location: ".$_SESSION['config']['businessappurl']."index.php?page=apa_add&module=advanced_physical_archive");
                exit;
            }
            elseif($mode == "up")
            {
                header("location: ".$_SESSION['config']['businessappurl']."index.php?page=apa_up&module=advanced_physical_archive&id=".$_SESSION['apa']['header']['headerId']);
                exit;
            }
        }
        else
        {
            $func = new functions();
            $alert = false;
            require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_request.php');
            $req = new request();
            //$creationdate = date('Y-m-d');
            $creationdate = $req->current_datetime();
            $entity = $_SESSION['user']['primaryentity']['id'];

            //Format the date to database format
            $databaseformat_destructdate = $func->format_date_db($_SESSION['apa']['header']['destructdate']);
            $databaseformat_allowdate = "'".$func->format_date_db($_SESSION['apa']['header']['allowdate'])."'";
            if($databaseformat_allowdate = "''")
            {
                $databaseformat_allowdate = 'NULL';
            }
            $_SESSION['apa']['header']['numuc'] = (int) $_SESSION['apa']['header']['numuc'];
            //If the container field is not empty
            if (isset($_SESSION['apa']['header']['numuc']) && !empty($_SESSION['apa']['header']['numuc']))
            {
                //If the destruction date is the same for all ua in uc
                if(!$this->checkdestructdate($_SESSION['apa']['header']['numuc'], $databaseformat_destructdate))
                {
                    $_SESSION['error'] =_MANAGE_APA_UA_DIFFERENT_DATE."<br/>";
                }

                //If the container dont exist we create it
                if(!$this->checkarcontainer($_SESSION['apa']['header']['numuc'], $entity))
                {
                    $this->query("insert into ".$_SESSION['tablename']['apa_containers']." (entity_id, arcontainer_id, status,  ctype_id, creation_date, retention_time)
                    values
                    ('".$entity."', '".$_SESSION['apa']['header']['numuc']."','APA', '".$_SESSION['apa']['header']['ctypeid'] ."', ".$creationdate.", '".$databaseformat_destructdate."')");

                    $_SESSION['error'] .=_MANAGE_APA_UC_CREATED."<br/>";
                }
                else
                {
                    //The container exist, check if the user can use it
                    if(!$this->accessarcontainer($_SESSION['apa']['header']['numuc']))
                    {
                        $_SESSION['error'] .=_MANAGE_APA_UC_NO_ACCESS."<br/>";
                        $alert = true;
                    }
                }
            }
            // else
            // {
                // $_SESSION['apa']['header']['numuc'] = null;
            // }

            //If user have access right for uc
            if(!$alert)
            {
                //If the archiv box dont exist we create it
                if(!$this->checkarbox($_SESSION['apa']['header']['numua']))
                {
					$this->query("select last_value from arbox_id_seq");
					$arboxid=$this->fetch_object();
					$lastvalue=1+$arboxid->last_value;

                    $this->query("insert into ".$_SESSION['tablename']['apa_boxes']." (title, description, entity_id, arcontainer_id, status, creation_date, retention_time, custom_t3, custom_t2, custom_d2)
                    values
                    ('APA".$lastvalue."', '".$func->protect_string_db($_SESSION['apa']['header']['ardesc'])."', '".$_SESSION['apa']['header']['entityid']."', '".$_SESSION['apa']['header']['numuc']."', 'CLO', ".$creationdate.", '".$databaseformat_destructdate."', '".$_SESSION['user']['UserId']."', 'APA', ".$creationdate.") returning arbox_id");

                    //Get the las insert ID of ar_boxes (MYSQL)
                    //if (empty($_SESSION['apa']['header']['numua']))
                    //{
                    //  $_SESSION['apa']['header']['numua'] = $this->last_insert_id();
                    //}
                    //$this->show(); exit;
                    $res = $this->fetch_object();
                    $_SESSION['apa']['header']['numua'] = $res->arbox_id;

                    //echo "last arbox_id :".$_SESSION['apa']['header']['numua']; exit;
                    $_SESSION['error'] .=_MANAGE_APA_UA_CREATED."<br/>";
                }
                else
                {
                    //The archiv box exist, check if the user can  use it
                    if(!$this->accessarbox($_SESSION['apa']['header']['numua']))
                    {
                        $_SESSION['error'] =_MANAGE_APA_UA_NO_ACCESS."<br/>";
                        $alert = true;
                    }
                    else
                    {
                        if($mode == "add")
                        {
                            //Verify if a header with same box exist
                            if($this->archivealreadyexist($_SESSION['apa']['header']['numua']))
                            {
                                $_SESSION['error'] =_WARNING_ALREADY_EXIST_ARCHIVE."<br/>";
                                $alert = true;
                            }

                        }
                        elseif($mode == "up")
                        {
                            //In update mode, if the UC change alert the user et take the answer
                            if(!$this->boxincontainer($_SESSION['apa']['header']['numua'], $_SESSION['apa']['header']['numuc'], $entity) && empty($cancel))
                            {
                                ?>
                                <script type="text/javascript">
                                    var isComfirm = confirm('<?php echo addslashes(html_entity_decode(_MANAGE_APA_UA_NOT_IN_UC,  ENT_COMPAT, 'UTF-8'))."\\n\\n "; echo addslashes(html_entity_decode(_MANAGE_APA_UA_NOT_IN_UC_CONTINUE,  ENT_COMPAT, 'UTF-8')). ": ".$_SESSION['apa']['header']['numuc']." ?"; ?>');

                                    if (isComfirm == false)
                                    {
                                        window.top.location.href='<?php echo $_SESSION['config']['businessappurl']."index.php?page=list_apa&module=advanced_physical_archive";?>';
                                    }
                                    else if (isComfirm == true)
                                    {
                                        window.top.location.href='<?php echo $_SESSION['config']['businessappurl']."index.php?Kdisplay=true&module=advanced_physical_archive&page=apa_up_db&cancel=false";?>';
                                    }
                                </script>
                                <?php

                            }else
                            {   //Else whe put it in the new container
                                $cancel = "false";
                            }

                            //If the archive is repositionned
                            if ($cancel == "false")
                            {
                                //Update some fields in the box
                                $this->query("update ".$_SESSION['tablename']['apa_boxes']." set
                                                arcontainer_id = '".$_SESSION['apa']['header']['numuc']."',
                                                description = '".$func->protect_string_db($_SESSION['apa']['header']['ardesc'])."',
                                                retention_time = '".$databaseformat_destructdate."'
                                                where arbox_id = ".$_SESSION['apa']['header']['numua']);
                            }
                        }
                    }
                }
            }

            //Go back to the archive add form
            if($alert)
            {
                if($mode == "add")
                {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=apa_add&module=advanced_physical_archive");
                    exit;
                }
                elseif($mode == "up")
                {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=apa_up&module=advanced_physical_archive&id=".$_SESSION['apa']['header']['headerId']);
                    exit;
                }
            }
            else
            {
                if($mode == "add")
                {
                    $intarboxid = (int)$_SESSION['apa']['header']['numua'];
                    //Create header
                    $this->query("insert into ".$_SESSION['tablename']['apa_header']." (
                     creation_date, ctype_id, year_1, year_2, destruction_date, allow_transmission_date,
                    header_desc, entity_id, arnature_id, arbox_id, arcontainer_id)
                    values(
                    ".$creationdate.", '".$_SESSION['apa']['header']['ctypeid'] ."',".$_SESSION['apa']['header']['year1'] .",
                    ".$_SESSION['apa']['header']['year2'] .",
                    '".$databaseformat_destructdate."',".$databaseformat_allowdate.",
                    '".$func->protect_string_db($_SESSION['apa']['header']['ardesc'])."', '".$_SESSION['apa']['header']['entityid']."',
                    '".$_SESSION['apa']['header']['arnatureid']."', ".$intarboxid.", ".$_SESSION['apa']['header']['numuc'].")");

                    //Recupere le last ID
                    $sequence_name = 'ar_header_header_id_seq';
                    $last_insert_id = $this->last_insert_id($sequence_name); //!!!!!!!!

                    //If the header container is already positionned we update the arbox status
                    $tabUC = array();
                    $tabUC = $this->containerposition($_SESSION['apa']['header']['numuc'], $entity);
                    if (count($tabUC) >0)
                    {
                        //Update the arbox status
                        $this->query("update ".$_SESSION['tablename']['apa_boxes']." set status = 'POS' where arbox_id = '".$_SESSION['apa']['header']['numua']."'");

                        //Update the site in header
                        $this->query("update ".$_SESSION['tablename']['apa_header']." set site_id = '".$tabUC['site']."' where header_id = '".$last_insert_id."'");
                    }

                    //History
                    if($_SESSION['history']['archiveadd'] == "true")
                    {
                        require("core/class/class_history.php");
                        $hist = new history();
                        $hist->add($_SESSION['tablename']['apa_header'], $last_insert_id ,"ADD", 'archiveadd', _MANAGE_APA_HEADER_ADDED.": ".$_SESSION['apa']['header']['numua']."/".$_SESSION['apa']['header']['numuc'], $_SESSION['config']['databasetype'], 'advanced_physical_archive');
                    }

                    $_SESSION['error'] .= _MANAGE_APA_HEADER_ADDED;

                    //Go back to the add form screen
                    $_SESSION['apa']['header']['numua'] = "";
                    $_SESSION['apa']['header']['ardesc'] = "";

                    //$this->clearapainfos();

                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=apa_add&module=advanced_physical_archive");
                    exit;
                }
                elseif($mode == "up" && $cancel =="false")
                {
                    //Update header
                    $this->query("update ".$_SESSION['tablename']['apa_header']." set
                                    ctype_id = '".$_SESSION['apa']['header']['ctypeid'] ."',
                                    year_1 = '".$_SESSION['apa']['header']['year1'] ."',
                                    year_2 = '".$_SESSION['apa']['header']['year2'] ."',
                                    destruction_date = '".$databaseformat_destructdate."',
                                    allow_transmission_date = ".$databaseformat_allowdate.",
                                    header_desc = '".$func->protect_string_db($_SESSION['apa']['header']['ardesc'])."',
                                    entity_id = '".$_SESSION['apa']['header']['entityid']."',
                                    arnature_id = '".$_SESSION['apa']['header']['arnatureid']."',
                                    arbox_id = '".$_SESSION['apa']['header']['numua']."',
                                    arcontainer_id = '".$_SESSION['apa']['header']['numuc']."'
                                    where header_id = '".$_SESSION['apa']['header']['headerId']."'");

                    //History
                    if($_SESSION['history']['archiveup'] == "true")
                    {

                        require("core/class/class_history.php");
                        $hist = new history();
                        $hist->add($_SESSION['tablename']['apa_header'], $_SESSION['apa']['header']['headerId'] ,"UP", 'archiveup',  _MANAGE_APA_HEADER_UPDATED.": ".$_SESSION['apa']['header']['numua']."/".$_SESSION['apa']['header']['numuc'], $_SESSION['config']['databasetype'], 'advanced_physical_archive');
                    }

                    $_SESSION['error'] .= strtolower(_NUM).$_SESSION['apa']['header']['headerId'].' / '._MANAGE_APA_HEADER_UPDATED;

                    //Go back to the search result screen
                    $this->clearapainfos();
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=apa_search_result&module=advanced_physical_archive");
                    exit;
                }
            }
        }
    }

        /**
    * Add ou modify archive in the database
    *
    * @param $mode string up or add
    */
    public function apareturn()
    {
        $func = new functions();

        $_SESSION['apa']['return'] = array();

        //Get the archive id
        if(isset($_REQUEST['apaid']) && !empty($_REQUEST['apaid']))
        {
            $_SESSION['apa']['return']['id'] = $func->wash($_REQUEST['apaid'], "num", _NUM_ARCHIVE);
        }
        else
        {
            $_SESSION['error'] = _NUM_ARCHIVE." "._MANDATORY."<br>";
        }

        //If not error on archive id
        if(!empty($_SESSION['error']))
        {
            header("location: ".$_SESSION['config']['businessappurl']."index.php?page=apa_return&module=advanced_physical_archive");
            exit;
        }
        else
        {
            $this->connect();

            //Test ift the archive exist in header table
            //$this->query("select * from ".$_SESSION['tablename']['apa_header']." where header_id = '".$_SESSION['apa']['return']['id']."'");
            $this->query("select * from ".$_SESSION['tablename']['apa_header']." where arbox_id = '".$_SESSION['apa']['return']['id']."'");
            //$this->show();

            if($this->nb_result() == 0)
            {
                $_SESSION['error'] = _NO_ARCHIVE_CORRESPOND_TO_IDENTIFIER;
            }
            else
            {
                //echo 'nous sommes la!!!', die;
                $line = $this->fetch_object();

                //Verify if the current user can acces the archive box
                if(!$this->accessarbox($line->arbox_id))
                {
                    $_SESSION['error'] = _MANAGE_APA_UA_NO_ACCESS.' - '.$_SESSION['apa']['return']['id'];
                }
                else
                {
                    $alert1 = $alert2 = $alert3 = false;

                    //Test if the archive is out
                    //if($this->getarchivestatus($_SESSION['apa']['return']['id'], "OUT"))
                    if($this->getarboxstatus($_SESSION['apa']['return']['id']) == 'OUT')
                    {
                        //Current datetime
                        $thisDate = date('Y-m-d H:i:s');
                        $libaction = "";

                        //Get info from reservation table (res_apa))
                        //$req = $this->query("select res_id, arbox_id, custom_n1, custom_t2, custom_n2, origin from ".$_SESSION['tablename']['res_apa']. " where custom_n1 = ".$_SESSION['apa']['return']['id']." and status = 'OUT'", true);
                        $req = $this->query("select res_id, arbox_id, custom_n1, custom_t2, custom_n2, origin from ".$_SESSION['tablename']['res_apa']. " where arbox_id = ".$_SESSION['apa']['return']['id']." and status = 'OUT'", true);
                        //$this->show(); exit;

                        $res = $this->fetch_object();

                        if($this->nb_result() > 0)
                        {
                            $res_id = $res->res_id;
                            $boxid = $res->arbox_id;
                            $headerid = $res->custom_n1;
                            $siteid = $res->custom_t2;
                            $container = $res->custom_n2;
                            $entityid = $res->origin;

                            //Update reservation table
                            $req = $this->query("update ".$_SESSION['tablename']['res_apa']. " set status = 'POS', custom_d2 = '".$thisDate."' where res_id = ".$res_id, true);

                            if(!$req)
                            {
                                $alert1 = true;
                                $_SESSION['error'] = _SQL_ERROR;
                            }
                            else
                            {
                                //Update arbox status
                                $req = $this->query('UPDATE '.$_SESSION['tablename']['apa_boxes']." SET status = 'POS' WHERE arbox_id = '".$boxid."'");

                                if(!$req)
                                {
                                    $alert2 = true;
                                    $_SESSION['error'] = _SQL_ERROR;
                                }
                                else
                                {
                                    //Reset  reservation number in header
                                    $req = $this->query('UPDATE '.$_SESSION['tablename']['apa_header']." SET reservation_id = NULL WHERE header_id = '".$headerid."'");

                                    if(!$req)
                                    {
                                        $alert3 = true;
                                        $_SESSION['error'] = _SQL_ERROR;
                                    }
                                    else
                                    {
                                        $_SESSION['info'] = _ARCHIVE_RETURNED.' : '.$_SESSION['apa']['return']['id'];

                                        //Clean info
                                        $_SESSION['apa']['return'] = array();
                                        $_SESSION['apa']['return']['id'] = "";
                                    }

                                    //History
                                    if($_SESSION['history']['archiveout'] == "true")
                                    {
                                        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                                        $hist = new history();

                                        $libaction = _ARCHIVE_RETURNED;
                                        $action = 'RET';

                                        //Res_apa
                                        if (!$alert1) {$hist->add($_SESSION['tablename']['res_apa'], $headerid, $action, 'archiveout', $libaction, $_SESSION['config']['databasetype'], 'advanced_physical_archive');}
                                        //Arbox
                                        if (!$alert2) {$hist->add($_SESSION['tablename']['apa_boxes'], $boxid, $action, 'archiveout', $libaction, $_SESSION['config']['databasetype'], 'advanced_physical_archive');}
                                        //Header
                                        if (!$alert3) {$hist->add($_SESSION['tablename']['apa_header'], $headerid, $action, 'archiveout', $libaction, $_SESSION['config']['databasetype'], 'advanced_physical_archive');}
                                    }
                                }
                            }
                        }
                        else
                        {
                            $alert = true;
                            $_SESSION['error'] = _NO_ARCHIVE_CORRESPOND_TO_IDENTIFIER;
                        }
                    }
                    else
                    {
                        $_SESSION['error'] =_WARNING_NON_OUTED_ARCHIVE;
                    }
                }
            }
        }



        header("location: ".$_SESSION['config']['businessappurl']."index.php?page=apa_return&module=advanced_physical_archive");
        exit;
    }

    /**
    * form to search arvhive header
    *
    * @param $mode string mode of the operation
    */
    public function formapasearch($mode)
    {
        $func = new functions();

        if(!empty($_SESSION['error']))
        {
            header("location: ".$_SESSION['config']['businessappurl']."index.php?page=manage_apa&module=advanced_physical_archive");
            exit;
        }
        else
        {


            echo '<h1><img src="'.$_SESSION['config']['businessappurl'].'static.php?module=advanced_physical_archive&filename=picto_search_b.gif" alt="" /> '._MANAGE_APA_SEARCH.'</h1>';

            $link = $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=apa_search_result&mode=".$mode;

            ?>
            <script type="text/javascript">
            function GetXmlHttpObject()
            {
                var req = null;

                if (window.XMLHttpRequest)
                {
                    req = new XMLHttpRequest();
                }
                else if (window.ActiveXObject)
                {
                    try {
                        req = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e)
                    {
                        try {
                            req = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {}
                    }
                }
                return req;
            }

            function updateListBox(ID, Param, what, text)
            {

                //link to the PHP file your getting the data from
                var loaderphp = null;
                //window.alert('test');
                if (what =="nature")
                {
                    var loaderphp = "<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=nature_dropdown_loader";?>";
                }
                else
                {
                    var loaderphp = "<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=position_dropdown_loader";?>";
                }

                xmlHttp = GetXmlHttpObject();

                xmlHttp.onreadystatechange=function()
                {
                    if (xmlHttp.readyState == 1)
                    {
                        document.getElementById(ID).innerHTML = "Loading...";
                    }

                    if(xmlHttp.readyState==4)
                    {
                        //Reset list box when choose different site or level
                        if(ID =="rowbox" || ID =="colbox")
                        {
                            var resetLevelBox = '<select name="level" id="level" class="small"></select>';
                            document.getElementById('levelbox').innerHTML = resetLevelBox ;
                        }

                        if(ID =="rowbox")
                        {
                            var resetColBox = '<select name="col" id="col" class="small"></select>';
                            document.getElementById('colbox').innerHTML = resetColBox ;
                        }

                        //This set the dat from the php to the html
                        if (text == null)
                        {
                            document.getElementById(ID).innerHTML = xmlHttp.responseText;
                        }
                        else
                        {   //Text field
                            document.getElementById(ID).value = xmlHttp.responseText;
                        }
                    }
                }
                //alert(loaderphp+"?id="+ID+"&value="+Param);
                xmlHttp.open("GET", loaderphp+"&id="+ID+"&value="+Param,true);
                xmlHttp.send(null);
            }

            </script>
            <div id="inner_content">
                <div class="block">
                <form name="formapasearch" id="formapasearch" method="post" action="<?php echo $link; ?>" class="forms">
                    <input type="hidden"  name="display" value="true" />
                    <input type="hidden"  name="module" value="advanced_physical_archive" />
                    <input type="hidden"  name="page" value="apa_search_result" />
                    <input type="hidden"  name="siteid" value="<?php echo $siteid; ?>" />
                    <br>
                    <h2  align="center"><?php echo _MANAGE_APA_ARCHIVE_INFO;?> </h2>
                    <br/>
                    <table border="0" align="center">
                        <tr>
                        <?php
                        $_SESSION['apa']['containertypeslist'] = $this->getcontainertypes();
                        //$func->show_array($_SESSION['apa']['containertypeslist']);
                        ?>
                            <td width="25%" align="right"><?php echo _THE_CONTAINER_TYPES;?> :</td>
                            <td width="24%"nowrap>
                                <select name="ctypeid" id="ctypeid" class="selectform">
                                    <option value=""><?php echo _CHOOSE_CONTAINER_TYPES;?></option>
                                <?php
                                for($i=0;$i<count($_SESSION['apa']['containertypeslist']);$i++)
                                {
                                    if($_SESSION['apa']['containertypeslist'][$i]['id'] == $_SESSION['apa']['search']['ctypeid'])
                                    {
                                        ?>
                                            <option value="<?php echo $_SESSION['apa']['containertypeslist'][$i]['id'];?>" selected="selected"><?php echo $_SESSION['apa']['containertypeslist'][$i]['desc'];?></option>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <option value="<?php echo $_SESSION['apa']['containertypeslist'][$i]['id'];?>"><?php echo $_SESSION['apa']['containertypeslist'][$i]['desc'];?></option>
                                        <?php
                                    }
                                }
                                ?>
                                </select>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td align="left" colspan="2">
                                <a href="index.php?page=apa_search&module=advanced_physical_archive&erase=true"><img src="<?php echo $_SESSION['config']['businessappurl']."static.php?module=advanced_physical_archive&filename=reset.gif";?>" alt="" /><?php echo _NEW_SEARCH; ?></a>
                            </td>

                        </tr>
                        <tr>
                            <td align="right"><?php echo _MANAGE_APA_CUSTOMER;?> :</td>
                            <td width="24%" nowrap>
                                <select name="entityid" id="entityid" onchange="updateListBox('naturebox', this.value, 'nature');" class="selectform">
                                    <option value=""><?php echo _MANAGE_APA_CHOOSE_CUSTOMER;?></option>
                                    <?php
                                    for ($j=0; $j < count($_SESSION['user']['entities']); $j++)
                                    {
                                        /*
                                        if($_SESSION['user']['entities'][$j]['ENTITY_ID'] == $_SESSION['apa']['search']['entityid'])
                                        {
                                            ?>
                                                <option value="<?php echo $_SESSION['user']['entities'][$j]['ENTITY_ID'];?>" selected="selected"><?php echo $_SESSION['user']['entities'][$j]['ENTITY_LABEL'];?></option>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <option value="<?php echo $_SESSION['user']['entities'][$j]['ENTITY_ID'];?>"><?php echo $_SESSION['user']['entities'][$j]['ENTITY_LABEL'];?></option>
                                            <?php
                                        }
                                        */
                                        $this->getEntityListBoxTree($_SESSION['user']['entities'][$j]['ENTITY_ID'], '', $_SESSION['apa']['search']['entityid'], '' , $except = array());
                                    }
                                    ?>
                                </select>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td align="right"><?php echo _TYPE;?> :</td>
                            <td nowrap>
                            <?php
                            $this->connect();
                            $this->query("select arnature_id, arnature_desc
                                        from ".$_SESSION['tablename']['apa_natures']."
                                        where entity_id = '".$_SESSION['apa']['search']['entityid']."'
                                        order by arnature_desc");
                            ?>
                                <span id="naturebox">
                                    <select name="arnatureid" id="arnatureid" class="selectform">
                                        <option value=""><?php echo _CHOOSE_NATURE;?></option>
                                    <?php
                                        while($line = $this->fetch_object())
                                        {
                                            if($line->arnature_id == $func->show_str($_SESSION['apa']['search']['arnatureid']))
                                            {
                                                ?>
                                                    <option value="<?php echo $line->arnature_id ;?>" selected="selected"><?php echo $line->arnature_desc ;?></option>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <option value="<?php echo $line->arnature_id ;?>"><?php echo $line->arnature_desc ;?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                    </select>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_DATE1;?> :</td>
                            <td width="24%" nowrap>
                                <input type="text" name="year1" id="year1" class="year" value="<?php echo $_SESSION['apa']['search']['year1'] ;?>"/>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td align="right"><?php echo _MANAGE_APA_DATE2;?> :</td>
                            <td nowrap>
                                <input type="text" name="year2" id="year2" class="year" value="<?php echo $_SESSION['apa']['search']['year2'] ;?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_DEPOSIT_DATE;?> :</td>
                            <td width="24%">
                                <input name="vers_fromdate" type="text" id="vers_fromdate"  class="medium2" value="<?php echo $_SESSION['apa']['search']['vers_fromdate'] ;?>" onclick='showCalender(this)'/>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td align="right"><?php echo _MANAGE_APA_TO_DATE;?> :</td>
                            <td>
                                <input name="vers_todate" type="text" id="vers_todate"  class="medium2" value="<?php echo $_SESSION['apa']['search']['vers_todate'] ;?>" onclick='showCalender(this)'/>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_ALLOW_DATE;?> :</td>
                            <td width="24%">
                                <input name="allow_fromdate" type="text" id="allow_fromdate"  class="medium2" value="<?php echo $_SESSION['apa']['search']['allow_fromdate'] ;?>" onclick='showCalender(this)'/>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td align="right"><?php echo _MANAGE_APA_TO_DATE;?> :</td>
                            <td>
                                <input name="allow_todate" type="text" id="allow_todate"  class="medium2" value="<?php echo $_SESSION['apa']['search']['allow_todate'] ;?>" onclick='showCalender(this)'/>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_DESTRUCTION_DATE;?> :</td>
                            <td width="24%">
                                <input name="destruct_fromdate" type="text" id="destruct_fromdate"  class="medium2" value="<?php echo $_SESSION['apa']['search']['destruct_fromdate'] ;?>" onclick='showCalender(this)'/>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td align="right"><?php echo _MANAGE_APA_TO_DATE;?> :</td>
                            <td>
                                <input name="destruct_todate" type="text" id="destruct_todate"  class="medium2" value="<?php echo $_SESSION['apa']['search']['destruct_todate'] ;?>" onclick='showCalender(this)'/>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_NUM_UA;?> :</td>
                            <td width="24%" nowrap>
                                <input name="numua" type="text" id="numua" class="medium2" value="<?php echo $_SESSION['apa']['search']['numua'] ;?>" />
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td align="right"><?php echo _MANAGE_APA_NUM_UC;?> :</td>
                            <td nowrap>
                                <input name="numuc" type="text" id="numuc" class="medium2" value="<?php echo $_SESSION['apa']['search']['numuc'] ;?>" />
                            </td>
                        </tr>
                        <?php
                        if (empty($mode))
                        {
                        ?>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_HEADER_DESC;?> :</td>
                            <td colspan="4">
                                <textarea  cols="30" rows="2"  name="desc"  id="desc" ><?php echo $func->show_str($_SESSION['apa']['search']['desc']); ?></textarea>
                            <a href="<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=apa_search&module=advanced_physical_archive&mode=adv" class="advsearch"><?php echo _MANAGE_APA_ADVANCED_SEARCH;?></a>
                            </td>
                        </tr>
                        <?php
                        }
                        elseif ($mode =="adv")
                        {
                        ?>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_HEADER_DESC;?> :</td>
                            <td colspan="3">
                                <table border="0" bgcolor="#FBE8B4" cellpadding="2" cellspacing="0">
                                    <tr>
                                        <td><?php echo _MANAGE_APA_ADVANCED_SEARCH_ALL; ?></td>
                                        <td><input type="text" value="" name="desc_q" size="25"></td>
                                    </tr>
                                    <tr>
                                        <td nowrap><?php echo _MANAGE_APA_ADVANCED_SEARCH_EXACT; ?></td>
                                        <td><input type="text" size="25" value="" name="desc_epq"></td>
                                    </tr>
                                    <tr>
                                        <td nowrap><?php echo _MANAGE_APA_ADVANCED_SEARCH_ONE; ?></td>
                                        <td><input type="text" size="25" value="" name="desc_oq"></td>
                                    </tr>
                                    <tr>
                                        <td nowrap><?php echo _MANAGE_APA_ADVANCED_SEARCH_NONE; ?></td>
                                        <td><input type="text" size="25" value="" name="desc_eq"></td>
                                    </tr>
                                </table>
                            <td><a href="index.php?page=apa_search&module=advanced_physical_archive" class="advsearch"><?php echo _MANAGE_APA_SIMPLE_SEARCH;?></a></td>

                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td width="25%" align="right"><?php echo _SITE;?> :</td>
                            <td width="24%">
                            <?php
                            $mysites = array();

                            $mysites =  $this->getsites();
                            ?>
                                <select name="site" id="site" onchange="updateListBox('rowbox', this.value);" class="selectform">
                                    <option value=""><?php echo _CHOOSE_SITE;?></option>
                                    <?php
                                    for($k=0;$k<count($mysites);$k++)
                                    {
                                        if($mysites[$k]['id'] == $_SESSION['apa']['search']['site'])
                                        {
                                            ?>
                                                <option value="<?php echo $mysites[$k]['id'];?>" selected="selected"><?php echo $mysites[$k]['desc'];?></option>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <option value="<?php echo $mysites[$k]['id'];?>"><?php echo $mysites[$k]['desc'];?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td align="right"><?php echo _POSITIONS_ROW;?> :</td>
                            <td>
                                <span id="rowbox">
                                    <select name="row" id="row" onchange="updateListBox('colbox', this.value);" class="small">
                                        <option value=""><?php echo _POSITIONS_CHOOSE_ROW;?></option>
                                    </select>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right">&nbsp;</td>
                            <td width="24%">&nbsp;</td>
                            <td width="2%">&nbsp;</td>
                            <td align="right"><?php echo _POSITIONS_COL;?> :</td>
                            <td>
                                <span id="colbox">
                                    <select name="col" id="col" onchange="updateListBox('levelbox', this.value);"  class="small">
                                        <option value=""><?php echo _POSITIONS_CHOOSE_COL;?></option>
                                    </select>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="right">&nbsp;</td>
                            <td width="24%">&nbsp;</td>
                            <td width="2%">&nbsp;</td>
                            <td align="right"><?php echo _POSITIONS_LEVEL;?> :</td>
                            <td>
                                <span id="levelbox">
                                    <select name="level" id="level" class="small">
                                        <option value=""><?php echo _POSITIONS_CHOOSE_LEVEL;?></option>
                                    </select>
                                </span>
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <p align="center">
                        <input class="button" name="submit" type="submit" value="<?php echo _VALIDATE; ?>"/>
                        <input type="button" name="cancel" value="<?php echo _CANCEL; ?>" class="button"  onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=manage_apa&amp;module=advanced_physical_archive';"/>
                    </p>
                </form>
                </div>
                <div class="block_end">&nbsp;</div>
            </div>
        <?php
        }
    }

    /**
    * Delete archive in the database
    *
    * @param $archive integer archive identifier
    */
    public function delapa($archive)
    {
        $this->connect();

        if(!empty($_SESSION['error']))
        {
            header("location: ".$_SESSION['config']['businessappurl']."index.php?page=apa_search_result&module=advanced_physical_archive");
            exit;
        }
        else
        {
            //Test ift the archive exist
            $this->query("select * from ".$_SESSION['tablename']['apa_header']." where header_id = '".$archive."'");
            if($this->nb_result() == 0)
            {
                $_SESSION['error'] = _NO_ARCHIVE_CORRESPOND_TO_IDENTIFIER;
            }
            else
            {
                //Test if the archive is reserved
                if(!$this->getarchivestatus($archive, "RSV") && !$this->getarchivestatus($archive, "OUT"))
                {
                    $this->query("delete from ".$_SESSION['tablename']['apa_header']."  where header_id = '".$archive."'", "no");

                    if($_SESSION['history']['archivedel'] == "true")
                    {
                        require("core/class/class_history.php");
                        $hist = new history();
                        $hist->add($_SESSION['tablename']['apa_header'], $archive ,"DEL", 'archivedel', _MANAGE_APA_HEADER_DELETED, $_SESSION['config']['databasetype'], 'advanced_physical_archive');
                    }

                    $_SESSION['error'] = _MANAGE_APA_HEADER_DELETED;
                }
                else
                {
                    $_SESSION['error'] =_WARNING_DELETE_RESERVED_ARCHIVE;
                }
            }

            header("location: ".$_SESSION['config']['businessappurl']."index.php?page=apa_search_result&module=advanced_physical_archive");
            exit;

        }
    }

    /**
    * form to search arvhive header for return, taking and reservation
    *
    * @param $mode string mode of the operation
    */
    public function formapaflux($mode)
    {
        $func = new functions();

            echo '<h1><img src="'.$_SESSION['config']['businessappurl'].'static.php?module=advanced_physical_archive&filename=picto_apa_reserve_b.gif" alt="" /> '._ARCHIVE_RESERVATION.'</h1>';

            //$link = $_SESSION['config']['businessappurl']."advanced_physical_archive/apa_flux_db.php?mode=".$mode;

            ?>
            <script type='text/javascript'>
            // Two function to access javascript object in Ajax frame
            var globalEval =  function(script){
              if(window.execScript){
                return window.execScript(script);
              } else if(navigator.userAgent.indexOf('KHTML') != -1){ //safari, konqueror..
                  var s = document.createElement('script');
                  s.type = 'text/javascript';
                  s.innerHTML = script;
                  document.getElementsByTagName('head')[0].appendChild(s);
              } else {
                return window.eval(script);
              }
            }
            //
            function evalMyScripts(targetId) {
                var myScripts = document.getElementById(targetId).getElementsByTagName('script');
                for (var i=0; i<myScripts.length; i++) {
                    globalEval(myScripts[i].innerHTML);
                }
            }

            function getXhr(){
                var xhr = null;
                if(window.XMLHttpRequest) // Firefox et autres
                    xhr = new XMLHttpRequest();
                else if(window.ActiveXObject){ // Internet Explorer
                    try {
                            xhr = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e){
                        xhr = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                }
                else { // XMLHttpRequest non support� par le navigateur
                    alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
                    xhr = false;
                }
                return xhr;
            }

            // M�thode qui sera appel�e pour rafraichir dynamiquement la liste nature
            function natureListUpdate(){
                var xmlHttp = getXhr();
                // On d�fini ce qu'on va faire quand on aura la r�ponse
                xmlHttp.onreadystatechange = function(){
                    //On affiche le texte de chargement
                    if(xmlHttp.readyState == 1)
                        document.getElementById('naturebox').innerHTML = 'Loading...';
                    // On ne fait quelque chose que si on a tout re�u et que le serveur est ok
                    if(xmlHttp.readyState == 4 && xmlHttp.status == 200){
                        result = xmlHttp.responseText;
                        // On se sert de innerHTML pour afficher le resultat dans la div
                        document.getElementById('naturebox').innerHTML = result;
                    }
                }
                xmlHttp.open("POST", "<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=advanced_physical_archive&page=nature_list_dropdown_loader";?>", true);
                xmlHttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                // ici, on recup�re l'argument � poster
                selEnt = document.getElementById('entityid');
                entityIdSelected = selEnt.options[selEnt.selectedIndex].value;
                // envoi de l'argument
                xmlHttp.send("entityid="+entityIdSelected);

                //window.setTimeout("searchResultUpdate()", 1000);
            }

            // M�thode qui sera appel�e pour rafraichir dynamiquement le resultat de la recherche
            function searchResultUpdate(){
                var xhr = getXhr();
                // On d�fini ce qu'on va faire quand on aura la r�ponse
                xhr.onreadystatechange = function(){
                    //On affiche l'image de chargement
                    //if(xhr.readyState == 2)
                    if(xhr.readyState == 1)
                        document.getElementById('loading').style.display = 'block';
                    // On ne fait quelque chose que si on a tout re�u et que le serveur est ok
                    if(xhr.readyState == 4 && xhr.status == 200){
                        result = xhr.responseText;
                        // On se sert de innerHTML pour afficher le resultat dans la div
                        document.getElementById('searchResult').innerHTML = result;
                        document.getElementById('loading').style.display = 'none';
                        evalMyScripts('searchResult');
                    }
                }
                xhr.open("POST", "<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module=advanced_physical_archive&page=list_apa_flux'; ?>", true);
                xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                sendParam = '';
                // ici, on recup�re les arguments � poster
                if(document.getElementById('entityid')){
                    selEnt = document.getElementById('entityid');
                    sendParam = sendParam + "entityid="+selEnt.options[selEnt.selectedIndex].value;
                }
                natureIdSelected ='';
                //if(document.getElementById('entityid')){
                if(document.getElementById('arnatureid')){
                    selNat = document.getElementById('arnatureid');
                    sendParam = sendParam + "&arnatureid="+selNat.options[selNat.selectedIndex].value;
                }
                pageMode = '<?php echo $mode; ?>';

                if(document.getElementById('year1')){
                    sendParam = sendParam + "&year1="+document.getElementById('year1').value;
                }
                if(document.getElementById('year2')){
                    sendParam = sendParam + "&year2="+document.getElementById('year2').value;
                }
                if(document.getElementById('desc')){
                    sendParam = sendParam + "&desc="+document.getElementById('desc').value;
                }
                if(document.getElementById('desc_q')){
                    sendParam = sendParam + "&desc_q="+document.getElementById('desc_q').value;
                }
                if(document.getElementById('desc_epq')){
                    sendParam = sendParam + "&desc_epq="+document.getElementById('desc_epq').value;
                }
                if(document.getElementById('desc_oq')){
                    sendParam = sendParam + "&desc_oq="+document.getElementById('desc_oq').value;
                }
                if(document.getElementById('desc_eq')){
                    sendParam = sendParam + "&desc_eq="+document.getElementById('desc_eq').value;
                }

                sendParam = sendParam + "&mode="+pageMode;
                // envoi des arguments
                //xhr.send("entityid="+entityIdSelected+"&arnatureid="+natureIdSelected+"&desc="+desc+"&mode="+pageMode);
                xhr.send(sendParam);
            }

            var _oldInputFieldValue = ""; // valeur pr�c�dente du champ texte
            var _currentInputFieldValue = ""; // valeur actuelle du champ texte
            //var _resultCache=new Object(); // m�canisme de cache des requ�tes

            // tourne en permanence pour sugg�rer suite � un changement du champ texte
            function mainLoop(){
                _currentInputFieldValue = _inputField.value;
                if(_oldInputFieldValue != _currentInputFieldValue){
                    // var valeur=escapeURI(_currentInputFieldValue);
                    // var suggestions=_resultCache[_currentInputFieldValue];
                    // if(suggestions){ // la r�ponse �tait encore dans le cache
                        // metsEnPlace(valeur,suggestions)
                    // }
                    // else{
                        // callSuggestions(valeur) // appel distant
                    // }

                    _inputField.focus()
                }
                _oldInputFieldValue = _currentInputFieldValue;
                setTimeout("mainLoop()",200); // la fonction se red�clenchera dans 200 ms
                return true
            }
            </script>
        <div id="inner_content">
            <div class="block">
                <!--<form name="formapareturn" id="formapareturn" method="post" action="<?php echo $link; ?>" class="forms">-->
                <form name="formapareturn" id="formapareturn" method="post" action="#" class="forms">
                    <input type="hidden"  name="display" value="true" />
                    <input type="hidden"  name="module" value="advanced_physical_archive" />

                    <input type="hidden"  name="siteid" value="<?php echo $siteid; ?>" />
                    <br>
                    <h2  align="center"><?php echo _MANAGE_APA_ARCHIVE_INFO;?> </h2>
                    <br/>
                    <table border="0" align="center">
                        <tr>
                            <td align="right"><?php echo _MANAGE_APA_CUSTOMER;?> :</td>
                            <td width="24%" nowrap="nowrap">
                                <select name="entityid" id="entityid" class="selectform" onchange="natureListUpdate()" >
                                    <option value=""><?php echo _MANAGE_APA_CHOOSE_CUSTOMER;?></option>
                                    <?php
                                    for ($j=0; $j < count($_SESSION['user']['entities']); $j++)
                                    {
                                        if($_SESSION['user']['entities'][$j]['ENTITY_ID'] == $_SESSION['apa']['search']['entityid'])
                                        {
                                            ?>
                                                <option value="<?php echo $_SESSION['user']['entities'][$j]['ENTITY_ID'];?>" selected="selected"><?php echo $_SESSION['user']['entities'][$j]['ENTITY_LABEL'];?></option>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <option value="<?php echo $_SESSION['user']['entities'][$j]['ENTITY_ID'];?>"><?php echo $_SESSION['user']['entities'][$j]['ENTITY_LABEL'];?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td width="2%">&nbsp;</td>
                            <td align="right"><?php echo _NATURE;?> :</td>
                            <td nowrap="nowrap">
                            <?php
                            $this->connect();
                            $this->query("select arnature_id, arnature_desc
                                        from ".$_SESSION['tablename']['apa_natures']."
                                        where entity_id = '".$_SESSION['apa']['search']['entityid']."'
                                        order by arnature_desc");
                            ?>
                                <span id="naturebox">
                                    <select name="arnatureid" id="arnatureid" class="selectform">
                                        <option value=""><?php echo _CHOOSE_NATURE;?></option>
                                    <?php
                                        while($line = $this->fetch_object())
                                        {
                                            if($line->arnature_id == $func->show_str($_SESSION['apa']['search']['arnatureid']))
                                            {
                                                ?>
                                                    <option value="<?php echo $line->arnature_id ;?>" selected="selected"><?php echo $line->arnature_desc ;?></option>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <option value="<?php echo $line->arnature_id ;?>"><?php echo $line->arnature_desc ;?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                    </select>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><?php echo _MANAGE_APA_YEAR1; ?> :</td>
                            <td><input type="text" name="year1" id="year1" class="year" value=""/></td>
                            <td></td>
                            <td align="right"><?php echo _MANAGE_APA_YEAR2; ?> :</td>
                            <td><input type="text" name="year2" id="year2" class="year" value=""/></td>
                        </tr>
                        <?php
                        //if (empty($mode))
                        if (empty($mode) || $mode =="rsv")
                        {
                        ?>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_HEADER_DESC;?> :</td>
                            <td colspan="4">
                                <textarea  cols="30" rows="2"  name="desc"  id="desc" ></textarea>
                            <a href="<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=apa_flux&module=advanced_physical_archive&mode=adv" class="advsearch"><?php echo _MANAGE_APA_ADVANCED_SEARCH;?></a>
                            </td>
                        </tr>
                        <?php
                        }
                        elseif ($mode =="adv")
                        {
                        ?>
                        <tr>
                            <td width="25%" align="right"><?php echo _MANAGE_APA_HEADER_DESC;?> :</td>
                            <td colspan="3">
                                <table border="0" bgcolor="#FBE8B4" cellpadding="2" cellspacing="0">
                                    <tr>
                                        <td><?php echo _MANAGE_APA_ADVANCED_SEARCH_ALL; ?></td>
                                        <td><input type="text" size="25" value="" id="desc_q" name="desc_q"></td>
                                    </tr>
                                    <tr>
                                        <td nowrap><?php echo _MANAGE_APA_ADVANCED_SEARCH_EXACT; ?></td>
                                        <td><input type="text" size="25" value="" id="desc_epq" name="desc_epq"></td>
                                    </tr>
                                    <tr>
                                        <td nowrap><?php echo _MANAGE_APA_ADVANCED_SEARCH_ONE; ?></td>
                                        <td><input type="text" size="25" value="" id="desc_oq" name="desc_oq"></td>
                                    </tr>
                                    <tr>
                                        <td nowrap><?php echo _MANAGE_APA_ADVANCED_SEARCH_NONE; ?></td>
                                        <td><input type="text" size="25" value="" id="desc_eq" name="desc_eq"></td>
                                    </tr>
                                </table>
                            </td>
                            <td><a href="<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=apa_flux&module=advanced_physical_archive" class="advsearch"><?php echo _MANAGE_APA_SIMPLE_SEARCH;?></a></td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td colspan="5" align="center">
                                <input type="button" name="submit" class="button" value="<?php echo _VALIDATE; ?>" onclick="javascript:searchResultUpdate();"/>
                                <input type="button" name="cancel" class="button" value="<?php echo _CANCEL; ?>" onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=manage_apa&amp;module=advanced_physical_archive';"/>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="block_end">&nbsp;</div>
            <br />
            <div id="searchResult"><i><br><?php echo _HAVE_TO_SELECT_CUTOMER; ?><br></i></div>
            <div id="loading" style="display:none;text-align:center;"><p><img src="<?php echo $_SESSION['config']['businessappurl'].'static.php?module=advanced_physical_archive&filename=ajax_loader.gif'; ?>" alt=""/></p></div>
        </div>
        <?php


    }

    /**
    * Reserve, taking or return an archive
    *
    * @param $id array of id
    * @param $mode string up or add
    */
    public function apafluxdb($id, $mode)
    {
        if(empty($_SESSION['error']))
        {
            $_SESSION['apa']['header']['uaIDs'] = array();
            $_SESSION['apa']['header']['uaIDs'] = $id;

            $nbArchiveTreated = 0;
            //print_r($_SESSION['apa']['header']['uaIDs']); exit;
            if($mode == 'rsv')
            {
                for ($i = 0; $i < count($_SESSION['apa']['header']['uaIDs']); $i++)
                {
                    //Verify if the id exist an if the archive is positionned
                    if(!$this->getarchivestatus($_SESSION['apa']['header']['uaIDs'][$i], 'POS'))
                    {
                        $_SESSION['error'].= _WARNING_ALREADY_RESERVED_ARCHIVE.': '.$_SESSION['apa']['header']['uaIDs'][$i].'<br>';
                    }
                    else
                    {
                        $func = new functions();

                        $this->connect();
                        $this->query('SELECT h.header_id, h.arbox_id, h.arcontainer_id, h.arnature_id, h.header_desc, c.position_id, h.entity_id, h.site_id FROM '.$_SESSION['tablename']['apa_header'].' h, '.$_SESSION['tablename']['apa_containers']." c WHERE h.arcontainer_id = c.arcontainer_id AND h.header_id = '".$_SESSION['apa']['header']['uaIDs'][$i]."'");
                        //$this->show(); exit;
                        if($this->nb_result() > 0)
                        {
                            $line = $this->fetch_object();

                            //Verify if the current user can acces the archive box
                            if(!$this->accessarbox($line->arbox_id))
                            {
                                $_SESSION['error'].= _MANAGE_APA_UA_NO_ACCESS.' - '.$_SESSION['apa']['header']['uaIDs'][$i].'<br>';
                            }
                            else //l'archive est disponible et l'utilisateur y a acces
                            {
                                // attribution du ticket si l'utilisateur n'en a pas
                                if(!isset($_SESSION['apa']['reservation_id']) || empty($_SESSION['apa']['reservation_id']))
                                {
                                    $_SESSION['apa']['reservation_id'] = $this->get_reservation_batch();
                                }

                                //Current datetime
                                $reservationDate = date('Y-m-d H:i:s');

                                //Insert into res_x
                                $this->query('INSERT INTO '.$_SESSION['tablename']['res_apa']." ( status, work_batch, custom_n1, custom_t2, custom_n2, arbox_id, custom_t3, description, custom_t4, creation_date, author, origin, custom_t15) VALUES ('RSV', '".$_SESSION['apa']['reservation_id']."', '".$_SESSION['apa']['header']['uaIDs'][$i]."', '".$line->site_id."', '".$line->arcontainer_id."', '".$line->arbox_id."', '".$line->arnature_id."', '".$func->protect_string_db($line->header_desc)."', '".$line->position_id."', '".$reservationDate."', '".$_SESSION['user']['UserId']."', '".$line->entity_id."', '".utf8_encode(addslashes($_SESSION['apa']['reservation_comment']))."')");

                                //Update arbox status
                                $this->query('UPDATE '.$_SESSION['tablename']['apa_boxes']." SET status = 'RSV' WHERE arbox_id = '".$line->arbox_id."'");

                                //Update header reservation Id
                                $this->query('UPDATE '.$_SESSION['tablename']['apa_header']." SET reservation_id = '".$_SESSION['apa']['reservation_id']."' WHERE header_id = '".$_SESSION['apa']['header']['uaIDs'][$i]."'");

                                //History
                                if($_SESSION['history']['archiveres'] == "true")
                                {
                                    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                                    $hist = new history();
                                    //Res_x
                                    $hist->add($_SESSION['tablename']['res_apa'], $_SESSION['apa']['header']['uaIDs'][$i], "ADD", 'archiveres', _ARCHIVE_RESERVED, $_SESSION['config']['databasetype'], 'advanced_physical_archive');
                                    //Arbox
                                    $hist->add($_SESSION['tablename']['apa_boxes'], $line->arbox_id, "RSV", 'archiveres', _ARCHIVE_RESERVED, $_SESSION['config']['databasetype'], 'advanced_physical_archive');
                                    //Header
                                    $hist->add($_SESSION['tablename']['apa_header'], $_SESSION['apa']['header']['uaIDs'][$i], "RSV", 'archiveres', _ARCHIVE_RESERVED, $_SESSION['config']['databasetype'], 'advanced_physical_archive');
                                }

                                $nbArchiveTreated++;

                            }
                        }
                    }
                }

                if($nbArchiveTreated > 0)
                {
                    $_SESSION['info'].= $nbArchiveTreated.' '._ARCHIVE_RESERVED;
                }

                //ouverture de la popup du comment
                //$_SESSION['apa']['reservation_id']
            }
            unset($_SESSION['apa']['header']['uaIDs']);
            unset($_SESSION['apa']['reservation_comment']);
        }

        ?>
        <script  type="text/javascript">
            window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=apa_flux&module=advanced_physical_archive&mode=<?php echo $mode; ?>';
        </script>
        <?php

        exit;

    }

    /*

    */
    public function formprint($id, $mode_str = false)
    {
        $func = new functions();
        $entitylist = "";
        //Get the list of entities for current user
        $entitylist = $this->getentitylist();
        $where = " entity_id in(".$entitylist.")";
        $this->connect();
        $this->query("select site_id, site_desc from ".$_SESSION['tablename']['apa_sites']." where ".$where." order by site_desc");
        $str = '';
        $str .= '<form id="form" name="form" method="get" action="'.$_SESSION['config']['businessappurl'].'index.php?display=true&module=advanced_physical_archive&page=apa_print_pdf" target="_blank">';
        $str .= '<input type="hidden" name="display" value="true" />';
        $str .= '<input type="hidden" name="module" value="advanced_physical_archive" />';
        $str .= '<input type="hidden" name="page" value="apa_print_pdf" />';
        $str .= '<script type="text/javascript">';
            $str .= 'function GetXmlHttpObject()';
            $str .= '{';
                $str .= 'var req = null;';
                $str .= 'if (window.XMLHttpRequest)';
                $str .= '{';
                    $str .= 'req = new XMLHttpRequest();';
                $str .= '}';
                $str .= 'else if (window.ActiveXObject)';
                $str .= '{';
                    $str .= 'try {';
                        $str .='req = new ActiveXObject("Msxml2.XMLHTTP");';
                    $str .= '} catch (e)';
                    $str .= '{';
                        $str .= 'try {';
                            $str .= 'req = new ActiveXObject("Microsoft.XMLHTTP");';
                        $str .= '} catch (e) {}';
                    $str .= '}';
                $str .='}';
                $str .='return req;';
            $str .= '}';

            $str .= 'function updateListBox(ID, Param, what, text)';
            $str .= '{';
                $str .= 'if(document.getElementById(ID)){';
                    //link to the PHP file your getting the data from
                    $str .= 'var loaderphp = null;';

                    $str .='if (what =="nature")';
                    $str .= '{';
                    $str .= 'var loaderphp = "'.$_SESSION['config']['businessappurl'].'index.php?display=true&module=advanced_physical_archive&page=nature_dropdown_loader";';
                    $str .= '}';
                    $str .= 'else if (what =="position")';
                    $str .='{';
                        $str .= 'var loaderphp = "'.$_SESSION['config']['businessappurl'].'index.php?display=true&module=advanced_physical_archive&page=position_dropdown_loader";';
                    $str .= '}';

                    $str .= 'xmlHttp = GetXmlHttpObject();';

                    $str .= 'xmlHttp.onreadystatechange=function()';
                    $str .= '{';
                    $str .= 'if (xmlHttp.readyState == 1)';
                        $str .= '{';
                            $str .= 'document.getElementById(ID).innerHTML = "Loading...";';
                        $str .= '}';

                    $str .= 'if(xmlHttp.readyState==4)';
                        $str .= '{';
                            //This set the dat from the php to the html
                            $str .= 'if (text == null)';
                            $str .= '{';
                                $str .= 'document.getElementById(ID).innerHTML = xmlHttp.responseText;';
                            $str .= '}';
                            $str .= 'else';
                        $str .= '{';    //Text field
                                $str .= 'document.getElementById(ID).value = xmlHttp.responseText;';
                            $str .= '}';
                    $str .= '   }';
                    $str .= '}';
                    //alert(loaderphp+"?id="+ID+"&value="+Param);
                    $str .= 'xmlHttp.open("GET", loaderphp+"&id="+ID+"&value="+Param,true);';
                    $str .= 'xmlHttp.send(null);';
            $str .= '   }';
            $str .= '}';
        $str .= '</script>';

        $str .= '<input name="id" type="hidden" value="'.$id.'" />';
        $str .= '<div class="block">';
            $str .= '<table width="95%"  border="0" cellspacing="0" cellpadding="5" align="center">';
                $str .= '<tr>';
                    $str .= '<td colspan="5" align="center"><h3>';

                        if($id == 'cust_nat')
                        {
                            $str .= _APA_PRINT_INVENTORY_LIST_CUSTMER_NAT;
                        }
                        elseif($id == 'cust_uc')
                        {
                            $str .= _APA_PRINT_INVENTORY_LIST_CUSTOMER_UC;
                        }
                        elseif($id == 'ret_date')
                        {
                            $str .= _APA_PRINT_RETURN_BY_PERIOD;
                        }
                        elseif($id == 'out_date')
                        {
                            $str .= _APA_PRINT_OUT_NOT_RETURN;
                        }
                        elseif($id == 'del_uc'){
                            echo _APA_PRINT_UC_DESTRUCTION;
                        }
                        elseif($id == 'nbr_res')
                        {
                            $str .= _APA_PRINT_RESERVATION_BY_CUSTOMER_DATE;
                        }
                        elseif($id == 'ratio_site')
                        {
                            $str .= _APA_PRINT_RATIO_BY_SITE_BOX;
                        }
                        elseif($id == 'in_out_date')
                        {
                            $str .= _APA_PRINT_IN_OUT_BY_CUSTOMER_DATE;
                        }
                        elseif($id == 'docket_rsv')
                        {
                            $str .= _APA_PRINT_DOCKET_RESERVATION;
                        }

                    $str .='</h3></td>';
                $str .='</tr>';
            if ($id == 'docket_rsv')
            {
                $str .='<tr>';
                            $str .='<td width="25%" align="right">'._ARCHIVE_RESERVATION_BATCH.' :</td>';
                            $str .='<td width="24%" nowrap>';
                                $str .='<input name="docketRsv" type="text" id="docketRsv" class="medium2" value=""/>';
                            $str .='</td>';
                            $str .='<td width="2%">&nbsp;</td>';
                            $str .='<td width="25%" align="right">&nbsp;</td>';
                            $str .='<td width="24%" nowrap>&nbsp;</td>';
                    $str .='</tr>';
            }
            else
            {
                $str .='<tr>';
                    $str .='<td align="right">'._SITE.' :</td>';
                    $str .='<td width="24%" nowrap>';
                        $str .='<select name="siteid" id="siteid" class="selectform">';

                            while($line = $this->fetch_object())
                            {
                                if($line->site_id == $_SESSION['apa']['site'])
                                {
                                    $str .='<option value="'.$line->site_id.'" selected="selected">'.$func->show_string($line->site_desc).'</option>';
                                }
                                else
                                {
                                    $str .='<option value="'.$line->site_id.'">'.$func->show_str($line->site_desc).'</option>';
                                }
                            }
                        $str .='</select>';
                    $str .='</td>';
                    $str .='<td width="2%">&nbsp;</td>';
                    $str .='<td align="right">'._MANAGE_APA_CUSTOMER.' :</td>';
                    $str .='<td nowrap>';
                        $str .='<select name="entityid" id="entityid" class="selectform" onchange="updateListBox(\'naturebox\', this.value, \'nature\');">';
                            $str .='<option value="">'._MANAGE_APA_CHOOSE_CUSTOMER.'</option>';

                            for ($j=0; $j < count($_SESSION['user']['entities']); $j++)
                            {
                                $str.= $this->getEntityListBoxTree($_SESSION['user']['entities'][$j]['ENTITY_ID'], '', $_SESSION['apa']['header']['entityid'], '' , $except = array(), true);
                            }
                        $str .='</select>';
                    $str .='</td>';
                $str .='</tr>';
                if($id == 'cust_nat')
                {
                    $str .='<tr>';
                    $str .='<td width="25%" align="right">'._NATURE.' :</td>';
                    $str .='<td width="24%" nowrap>';
                        $str .='<span id="naturebox">';
                            $str .='<select name="arnatureid" id="arnatureid" class="selectform">';
                                $str .='<option value="">'._CHOOSE_NATURE.'</option>';
                            $str .='</select>';
                        $str .='</span>';
                    $str .='</td>';
                    $str .='<td width="2%">&nbsp;</td>';
                    $str .='<td width="25%" align="right">'._MANAGE_APA_YEAR.' :</td>';
                    $str .='<td width="24%" nowrap>';
                        $str .='<input type="text" name="year" id="year" class="year" value=""/>';
                    $str .='</td>';
                $str .='</tr>';
                }
                elseif($id == 'cust_uc')
                {
                    $str .='<tr>';
                        $str .='<td width="25%" align="right">'._MANAGE_APA_NUM_UC.' :</td>';
                        $str .='<td width="24%" nowrap>';
                            $str .='<input name="numuc" type="text" id="numuc" class="medium2" value="" />';
                        $str .='</td>';
                        $str .='<td width="2%">&nbsp;</td>';
                        $str .='<td width="25%" align="right">&nbsp;</td>';
                        $str .='<td width="24%" nowrap>&nbsp;</td>';
                    $str .='</tr>';
                }
                elseif($id == 'ret_date')
                {
                    $str .='<tr>';
                        $str .='<td width="25%" align="right">'._SINCE.' :</td>';
                        $str .='<td width="24%" nowrap>';
                            $str .='<input name="begindate" type="text" id="begindate"  class="medium2" value="" onclick="showCalender(this);"/>';
                        $str .='</td>';
                        $str .='<td width="2%">&nbsp;</td>';
                        $str .='<td width="25%" align="right">'._FOR.' :</td>';
                        $str .='<td width="24%" nowrap>';
                            $str .='<input name="enddate" type="text" id="enddate"  class="medium2" value="" onclick="showCalender(this);"/>';
                        $str .='</td>';
                    $str .='</tr>';
                }
                elseif($id == 'out_date')
                {
                /*?>
                    <!--<tr>
                        <td width="25%" align="right"><?php //echo _DATE;?> :</td>
                        <td width="24%" nowrap>
                            <input name="date" type="text" id="date"  class="medium2" value="<?php //echo date("d-m-Y"); ?>" onclick='showCalender(this)'/>
                        </td>
                        <td width="2%">&nbsp;</td>
                        <td width="25%" align="right">&nbsp;</td>
                        <td width="24%" nowrap>&nbsp;</td>
                    </tr>-->
                <?php*/
                }
                elseif($id == 'del_uc')
                {
                    $str .='<tr>';
                        $str .='<td align="right">'._MANAGE_APA_YEAR.' :</td>';
                        $str .='<td width="24%" nowrap>';
                            $str .='<select name="year" >';
                                $str .='<option value="">- - - -</option>';

                                $this->query('SELECT DISTINCT YEAR(destruction_date) as year  from '.$_SESSION['tablename']['apa_header'].' WHERE entity_id IN ('.$entitylist.') ORDER BY year DESC');
                                //$this->show();
                                if($this->nb_result() > 0)
                                {
                                    while($line = $this->fetch_object())
                                    {
                                        $str .='<option value="'.$line->year.'">'.$line->year.'</option>';
                                    }
                                }
                            $str .='</select>';
                        $str .='</td>';
                        $str .='<td width="2%">&nbsp;</td>';
                        $str .='<td align="right">&nbsp;</td>';
                        $str .='<td >&nbsp;</td>';
                    $str .='</tr>';

                }
                elseif ($id == 'nbr_res' || $id == 'in_out_date')
                {
                    $str .='<tr>';
                            $str .='<td width="25%" align="right">'._SINCE.' :</td>';
                            $str .='<td width="24%" nowrap>';
                                $str .='<input name="begindate" type="text" id="begindate"  class="medium2" value="" onclick="showCalender(this);"/>';
                            $str .='</td>';
                            $str .='<td width="2%">&nbsp;</td>';
                            $str .='<td width="25%" align="right"><?php echo _FOR;?> :</td>';
                            $str .='<td width="24%" nowrap>';
                                $str .='<input name="enddate" type="text" id="enddate"  class="medium2" value="" onclick="showCalender(this);"/>';
                            $str .='</td>';
                    $str .='</tr>';
                }
            }
            $str .='</table>';
            $str .='</div>';
            $str .='<div class="block_end">&nbsp;</div>';
            $str .='<br/>';
            $str .='<p align="center">';
                $str .='<input class="button" name="submit" type="submit" value="'._VALIDATE.'"/>';
            $str .='</p>';
        $str .='</form>';
        if($mode_str == true)
        {
            return $str;
        }
        else
        {
            echo $str;
        }
    }

    public function formimport()
    {
    ?>
        <form id="form" name="form" method="post" action="<?php echo $_SESSION['config']['businessappurl'].'index.php?page=apa_import_treat&module=advanced_physical_archive'; ?>" ENCTYPE="multipart/form-data">
        <div class="block">
            <table width="95%" border="0" cellspacing="0" cellpadding="5" align="center">
                <tr>
                    <td align="left" width="17%"><?php echo _MANAGE_APA_IMPORT_FILE;?> :</td>
                    <td align="left"><input type="file" name="importFile" /></td>
                </tr>
            </table>
        </div>
        <div class="block_end">&nbsp;</div>
        <br/>
        <p align="center">
            <input class="button" name="submit" type="submit" value="<?php echo _VALIDATE; ?>"/>
            <input type="button" name="cancel" value="<?php echo _CANCEL; ?>" class="button"  onclick="javascript:window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=manage_apa&amp;module=advanced_physical_archive';"/>
        </p>
        </form>
    <?php
    }

    /**
    * Add archive in the database
    *
    * @param $numLigne integer numlber of the line
    */
    public function addapaimport($numLigne)
    {
        require_once("modules".DIRECTORY_SEPARATOR."entities".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_entities.php");
        $func = new functions();
        $ent = new entity();

        $alert = false;

        require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_request.php');
        $req = new request();
        $creationdate = $req->current_datetime();
        //$creationdate = date('Y-m-d H:i:s');

        //Format the date to database format
        $databaseformat_destructdate = $func->format_date_db($_SESSION['apa']['import']['destructdate']);
        $databaseformat_allowdate = $func->format_date_db($_SESSION['apa']['import']['allowdate']);


        //If the container field is not empty
        if (isset($_SESSION['apa']['import']['numuc']) && !empty($_SESSION['apa']['import']['numuc']))
        {
            //If the destruction date is the same for all ua in uc
            if( !$this->checkdestructdate($_SESSION['apa']['import']['numuc'], $databaseformat_destructdate))
            {
                $_SESSION['import']['error'] .=_MANAGE_APA_UA_DIFFERENT_DATE.' &lt;'._MANAGE_APA_IMPORT_LINE_NUM.$numLigne.'&gt;'."<br/>";
            }

            //If the container dont exist we create it
            // getParentEntityId($entity_id) // isEnabledEntity($entity_id) // getTabChildrenId($parent = '', $where = '', $immediate_children_only = false)
            // $_SESSION['user']['primaryentity']['id']
            $_SESSION['apa']['import']['consEntityId'] = '';
            if($ent->getParentEntityId($_SESSION['apa']['import']['cusEntityId']) == $_SESSION['user']['primaryentity']['id']){
                $_SESSION['apa']['import']['consEntityId'] = $_SESSION['user']['primaryentity']['id'];
                if(!$this->checkarcontainer($_SESSION['apa']['import']['numuc'], $_SESSION['apa']['import']['consEntityId']))
                {
                    $this->query("insert into ".$_SESSION['tablename']['apa_containers']." (entity_id, arcontainer_id, status,  ctype_id, creation_date, retention_time) values ('".$_SESSION['apa']['import']['consEntityId']."', '".$_SESSION['apa']['import']['numuc']."','APA', '".$_SESSION['apa']['import']['ctypeid'] ."', '".$creationdate."', '".$databaseformat_destructdate."')");

                    $_SESSION['import']['uc_created']++;
                }
                else{
                    //The container exist, check if the user can use it
                    if(!$this->accessarcontainer($_SESSION['apa']['import']['numuc']))
                    {
                        $_SESSION['import']['error'] .=_MANAGE_APA_UC_NO_ACCESS.' &lt;'._MANAGE_APA_IMPORT_LINE_NUM.$numLigne.'&gt;'."<br/>";
                        $alert = true;
                    }
                }
            }
            else{
                $_SESSION['import']['error'] .=_MANAGE_APA_UC_NO_ACCESS.' &lt;'._MANAGE_APA_IMPORT_LINE_NUM.$numLigne.'&gt;'."<br/>";
                $alert = true;
            }
        }

        //If user have access right for uc
        if(!$alert)
        {
            $this->query("insert into ".$_SESSION['tablename']['apa_boxes']." (arbox_id, title, description, entity_id, arcontainer_id, status,
            creation_date, retention_time, custom_t3, custom_t2)
            values
            ('', 'APA_IMPORT', '".$func->protect_string_db($_SESSION['apa']['import']['ardesc'])."', '".$_SESSION['apa']['import']['cusEntityId']."',
            '".$_SESSION['apa']['import']['numuc']."', 'CLO', '".$creationdate."', '".$databaseformat_destructdate."',
            '".$_SESSION['user']['UserId']."', 'APA')");

            $sequence_name = 'arbox_id_seq';
            $numUa = $this->last_insert_id($sequence_name);
            $_SESSION['import']['ua_created']++;
        }
        /*if(!$alert)
        {
            // if the UA is filled
            if (isset($_SESSION['apa']['import']['numua']) && !empty($_SESSION['apa']['import']['numua']))
            {
                echo 'premier pas pour les UC'; die;
                //If the archiv box dont exist we create it
                if(!$this->checkarbox($_SESSION['apa']['import']['numua']))
                {
                    $this->query("insert into ".$_SESSION['tablename']['apa_boxes']." (arbox_id, title, description, entity_id, arcontainer_id, status,
                    creation_date, retention_time, custom_t3, custom_t2)
                    values
                    ('".$_SESSION['apa']['import']['numua']."', 'APA".$_SESSION['apa']['import']['numua']."',
                    '".$func->protect_string_db($_SESSION['apa']['import']['ardesc'])."', '".$_SESSION['apa']['import']['cusEntityId']."',
                    '".$_SESSION['apa']['import']['numuc']."', 'NEW', '".$creationdate."', '".$databaseformat_destructdate."',
                    '".$_SESSION['user']['UserId']."', 'APA')");

                    //Get the las insert ID of ar_boxes
                    if (empty($_SESSION['apa']['import']['numua']))
                    {
                        $_SESSION['apa']['import']['numua'] = $this->last_insert_id();
                    }

                    //$_SESSION['import']['error'] .=_MANAGE_APA_UA_CREATED."<br/>";
                    $_SESSION['import']['ua_created']++;
                }
                else
                {
                    //The archiv box exist, check if the user can  use it
                    if(!$this->accessarbox($_SESSION['apa']['import']['numua']))
                    {
                        $_SESSION['import']['error'] .=_MANAGE_APA_UA_NO_ACCESS.' &lt;'._MANAGE_APA_IMPORT_LINE_NUM.$numLigne.'&gt;'."<br/>";
                        $alert = true;
                    }
                    else
                    {
                        //Verify if a header with same box exist
                        if($this->archivealreadyexist($_SESSION['apa']['import']['numua']))
                        {
                            $_SESSION['import']['error'] .=_WARNING_ALREADY_EXIST_ARCHIVE.' &lt;'._MANAGE_APA_IMPORT_LINE_NUM.$numLigne.'&gt;'."<br/>";
                            $alert = true;
                        }
                    }
                }
            }
        }*/

        if(!$alert)
        {
            //Create header
            $this->query("insert into ".$_SESSION['tablename']['apa_header']." (
            creation_date, ctype_id, year_1, year_2, destruction_date, allow_transmission_date,
            header_desc, entity_id, arnature_id, arbox_id, arcontainer_id)
            values(
            '".$creationdate."', '".$_SESSION['apa']['import']['ctypeid'] ."','".$_SESSION['apa']['import']['year1'] ."',
            '".$_SESSION['apa']['import']['year2'] ."',
            '".$databaseformat_destructdate."','".$databaseformat_allowdate."',
            '".$func->protect_string_db($_SESSION['apa']['import']['ardesc'])."', '".$_SESSION['apa']['import']['cusEntityId']."',
            '".$_SESSION['apa']['import']['arnatureid']."', '".$numUa."', '".$_SESSION['apa']['import']['numuc']."')");

            $sequence_name = 'ar_header_header_id_seq';
            $last_insert_id = $this->last_insert_id($sequence_name); //!!!!!!!!

            //If the header container is already positionned we update the arbox status
            $tabUC = array();
            $tabUC = $this->containerposition($_SESSION['apa']['import']['numuc'], $_SESSION['apa']['import']['cusEntityId']);
            if (count($tabUC) >0)
            {
                //Update the arbox status
                $this->query("update ".$_SESSION['tablename']['apa_boxes']." set status = 'POS' where arbox_id = '".$numUa."'");

                //Update the site in header
                $this->query("update ".$_SESSION['tablename']['apa_header']." set site_id = '".$tabUC['site']."' where header_id = '".$last_insert_id."'");
            }

            //History
            if($_SESSION['history']['archiveadd'] == "true")
            {
                require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                $hist = new history();
                $hist->add($_SESSION['tablename']['apa_header'], $last_insert_id ,"ADD", 'archiveadd',
                _MANAGE_APA_HEADER_ADDED.": ".$numUa."/".$_SESSION['apa']['import']['numuc'], $_SESSION['config']['databasetype'], 'advanced_physical_archive');
            }

            $_SESSION['import']['header_add']++;
            $this->clearapaimportinfos();
        }
    }
}
?>
