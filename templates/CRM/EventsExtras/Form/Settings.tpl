{*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2018                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*}
<div class="crm-block crm-form-block crm-events-extras-form-block">
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
  {foreach from=$displaySections item=section}
    <h3>{ts}{$section.name}{/ts}</h3>
    {foreach from=$section.fields item=elementName}
      <div class="crm-section {$elementName}">
         {if $form.$elementName.type neq 'checkbox'}
          <div class="label">
            {$form.$elementName.label}
            {if !$parentSettings.$elementName} {help id=$form.$elementName.name}
            {/if}
         </div>
        {/if}
      <div class="content">
          {$form.$elementName.html}
          {if $form.$elementName.type eq 'checkbox'}
            {$form.$elementName.label}{if !$parentSettings.$elementName} {help id=$form.$elementName.name}
          {/if}
          {if !$parentSettings.$elementName}
            <div class="description">{$settingsDescription.$elementName}</div>
          {/if}
        {/if}
      </div>
      <div class="clear"></div>
      </div>
    {/foreach}
  {/foreach}
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>
  {literal}
  <script type="text/javascript">
    CRM.$(function($) {
    {/literal}
    {foreach from=$displaySections item=section}
     {foreach from=$section.fields item=elementName}
      {if $parentSettings.$elementName}
        {literal}
        //hide default option by default if show is selected
        if($("input[name='{/literal}{$form.$elementName.name}{literal}']").prop("checked") == true){
          $("[class*='{/literal}{$form.$elementName.name}{literal}_']").hide();
        }
        $("input[name='{/literal}{$form.$elementName.name}{literal}']").change(function() {
          var selectVale = $(this).val();
          if (selectVale == 1) {
            $("[class*='{/literal}{$form.$elementName.name}{literal}_']").hide();
          }else {
            $("[class*='{/literal}{$form.$elementName.name}{literal}_']").show();
          }
        });
        {/literal}
      {/if}
      {/foreach}
    {/foreach}
    {literal}
    });
  </script>
  {/literal}
