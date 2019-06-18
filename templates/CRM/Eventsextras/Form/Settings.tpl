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
  <h3>{ts}Front Page Settings{/ts}</h3>
  {foreach from=$frontpageConfigSection item=elementName}
    <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
       <div class="content">
        {$form.$elementName.html}
      </div>
    <div class="clear"></div>
    </div>
  {/foreach}
  <h3>{ts}Fees Settings{/ts}</h3>
  {foreach from=$feeConfigSection item=elementName}
    <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">
      {$form.$elementName.html}
    </div>
    <div class="clear"></div>
    </div>
  {/foreach}
  <h3>{ts}Online Registration Settings{/ts}</h3>
  {foreach from=$onlineRegistrationSection item=elementName}
    <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">
      {$form.$elementName.html}
    </div>
    <div class="clear"></div>
    </div>
    {/foreach}
    <div class="crm-submit-buttons">
      {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
</div>
