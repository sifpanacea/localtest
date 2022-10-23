<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Author: Daniel Davis
*         @ourmaninjapan
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.09.2013
*
* Description:  English language file for Ion Auth example views
*
*/

// Errors
$lang['error_csrf'] = 'This form post did not pass our security checks.';

//Common

$lang['common_dash_heading']	           	=	'<img src= '.(IMG."logo-tlstec.png").' height="100px" width="177px">';
$lang['common_message']						=	'讯息!';
$lang['common_create_user_link']			=	'<i class="icon-dash-large icon-user icon-white"></i><span class="hidden-tablet">  创建一个新的管理员 </span>';
$lang['common_create_group_link'] 			=	'<i class="icon-dash-large icon-edit icon-white"></i><span class="hidden-tablet">  创建新组 </span>';
$lang['common_design_template_link']		=	'设计模板';
$lang['common_change_password_link']	    =	'<i class="icon-dash-large icon-folder-close-alt icon-white"></i><span class="hidden-tablet">  更改密码 </span>';
$lang['common_design_template_link']		=	'<i class="icon-dash-large icon-font icon-white"></i><span class="hidden-tablet">  设计模板 </span>';
$lang['common_logout_link']					=	'<i class="icon-dash-large icon-lock icon-white"></i><span class="hidden-tablet"> 注销 </span>';
$lang['common_logout_link_small']			=	'<i class="icon-off"></i> Logout </span>';

$lang['common_userinfo_link']         		=	'用户信息';
$lang['common_admin_dash_link']        		=	'<i class="icon-dash-large icon-home icon-white"></i><span class="hidden-tablet">  仪表盘';
$lang['common_customers_dash_link']        		=	'<i class="icon-dash-large glyphicon icon-group"></i><span class="hidden-tablet">  客户';
$lang['admin_profile']                            =   '<i class="icon-user"></i> 轮廓 </span>';
$lang['admin_settings']                            =  '<i class="icon-wrench icon-white"></i> 设置 </span>';


$lang['common_copy_rights']					=	'&copy;2015, TLSTEC。版权所有.';
$lang['common_follow']		 				= ' - 跟着我们 -';
$lang['common_comp_name_req']		 		= '\'请输入您的公司名称.\'';
$lang['common_comp_name_min']		 		= '\'请输入至少5个字符。\'';
$lang['common_comp_name_max']		 		= '\'请输入不超过25个字符。\'';
$lang['common_comp_site_req']		 		= '\'请输入您的公司的网站。\'';
$lang['common_comp_addr_req']		 		= '\'请输入您公司的地址。\'';
$lang['common_email_req']	   				= '\'请输入您的电子邮件地址。\'';
$lang['common_email_valid']	   				= '\'请输入有效的电子邮件地址。\'';
$lang['common_pass_req']	   				= '\'请输入您的密码。\'';
$lang['common_pass_min']	   				= '\'请输入至少8个字符。\'';
$lang['common_pass_max']	   				= '\'请输入不超过25个字符。\'';
$lang['common_con_pass_req']	   			= '\'请输入您的密码一次。\'';
$lang['common_con_pass_min']	   			= '\'请输入至少8个字符。\'';
$lang['common_con_pass_max']	   			= '\'请输入不超过25个字符。\'';
$lang['common_con_pass_equal']	   			= '\'应该是一样的密码字段。\'';
$lang['common_user_req']	   				= '\'请输入您的用户名。\'';
$lang['common_plan_req']	   				= '\'请选择您的计划。\'';
$lang['common_customer_req']	   			= '\'请选择客户。\'';
$lang['common_mobile_req']	   				= '\'请输入您的手机号码。\'';
$lang['common_mobile_no']	   				= '\'请输入有效的数字。\'';
$lang['common_cp_req']	   					= '\'请输入联系人的姓名。\'';
$lang['common_tc_req']	   					= '\'您必须同意条款和条件。\'';
$lang['common_type_req']	   				= '\'请输入您的公司的类别。\'';

$lang['common_passage_1']	   		= '想象一下，你的精良办公会，如果你的办公室是无纸化呢！想体验？与我们携手！';
$lang['common_passage_2']	   		= '搬出的纸张，不过遗憾的关于键入基于Web的形式？';
$lang['common_passage_3']	   		= '别担心。填写您自己的笔迹形式。感到兴奋？想了解更多？只是领先一步！报名';
$lang['common_passage_4']	   		= '设计和快速地部署业务应用程序！';
$lang['common_passage_5']	   		= '我们不单单只让你设计你的应用程序。您也可以在几分钟内部署我们的安全的云你的应用程序，让您的企业进步轻松！';

// Activation pages
$lang['title']     			= '激活你的帐号PAAS';
$lang['act_sign']      		= '登入';
$lang['act_sub_con']      	= '认购确认！..你TLSTEC PaaS的帐户激活！ ！';
$lang['act_mail_sent']      = '你PaaS的帐户接受！确认电子邮件将被发送一次管理员激活您的账号！ ！';
$lang['act_mail_sent_api']      = '你的API帐户接受！确认电子邮件将被发送一次所选客户激活您的帐号！ ！';
$lang['forgot_password_mail_sent']   = '重置密码电子邮件发送！ ！';
$lang['act_home']      		= '家'; 


// Login
$lang['login_title']     			= '登录';
$lang['login_heading']         		= '<img src= '.(IMG."login_icon.png").' height="100px" width="177px">';
$lang['login_subheading']      		= '登入';
$lang['login_identity_label']  		= '电子邮件:';
$lang['login_identity_label_help']  = '输入电子邮件地址';
$lang['login_password_label']  		= '密码:';
$lang['login_password_label_help']  = '输入您的密码';
$lang['login_remember_label']  		= '保持登录状态';
$lang['login_submit_btn']     		= '登入';
$lang['login_forgot_password'] 		= '忘记密码了吗？';
$lang['login_need_acc'] 			= '需要一个帐户？';
$lang['login_create_acc'] 			= '创建账户';
$lang['login_create_api'] 			= '创建第三方账户';
$lang['login_email_req']	   		= '\'请输入您的电子邮件地址\'';
$lang['login_email_valid']	   		= '\'请输入有效的电子邮件地址\'';
$lang['login_pass_req']	   			= '\'请输入您的密码\'';


//TLSTEC create account page
$lang['signup_title']							    	= '报名';
$lang['signup_already_reg']							    	= '已经注册？';
$lang['signup_sign_in']							    	= '登入';
$lang['signup_subheading']      						= '在此注册';
$lang['signup_customer_company_name']           		= '公司名称';
$lang['signup_customer_company_name_help']           	= '输入您的公司名称';
$lang['signup_customer_company_website']  				= '公司网站';
$lang['signup_customer_company_website_help']  			= '输入您的公司网站';
$lang['signup_customer_company_address']        		= '公司地址';
$lang['signup_customer_company_email']					= '电子邮件';
$lang['signup_customer_company_email_help']	        	= '请输入您的电子邮件地址';
$lang['signup_customer_password'] 						= '密码';
$lang['signup_customer_password_help']					= '8到20个字符之间输入密码';
$lang['signup_customer_confirm_password'] 				= '确认密码';
$lang['signup_customer_confirm_password_help']			= '应该符合上述密码';
$lang['signup_customer_username'] 						= '用户名';
$lang['signup_customer_company_contact_mobile']			= '手机号码';
$lang['signup_customer_company_contact_person']			= '联络人';
$lang['signup_customer_company_contact_person_help']	= '请输入联系人的姓名。';
$lang['signup_customer_plan']							= '选择计划';
$lang['signup_customer_offers']							= '我想收到消息和特别优惠';
$lang['signup_customer_tc']								= '我同意 <a href="#" data-toggle="modal" data-target="#myModal"> 条款 </a>';
$lang['signup_customer_req']							= '注册';
$lang['signup_customer_thanks']							= '谢谢您注册！';
$lang['signup_customer_plan_bronze']							= '青铜';
$lang['signup_customer_plan_silver']							= '银';
$lang['signup_customer_plan_gold']							= '金';
$lang['signup_customer_plan_diamond']							= '钻石';

//TLSTEC create API account page
$lang['api_title']							    		= '后';
$lang['api_already_reg']							    	='已经注册？';
$lang['api_sign_in']							    	= '登录';
$lang['api_subheading']      						= '3<sup>rd</sup> 党 注册 这里';
$lang['api_customer_company_name']           		= '公司名称';
$lang['api_customer_company_name_help']           	= '输入您的公司名称';
$lang['api_customer_company_type']           		='公司名称';
$lang['api_customer_company_type_help']           	= '请输入您的公司名称';
$lang['api_customer_company_website']  				='公司网站';
$lang['api_customer_company_website_help']  		= '请输入您的公司\'网站';
$lang['api_customer_company_address']        		= '公司地址';
$lang['api_customer_primary']		        		= '主用户';
$lang['api_customer_username'] 						= '用户名';
$lang['api_customer_company_email']					= '电子邮件';
$lang['api_customer_company_email_help']	        = '请输入您的电子邮件地址';
$lang['api_customer_password'] 						= '密码';
$lang['api_customer_password_help']					= '8到20个字符之间输入密码';
$lang['api_customer_confirm_password'] 				= '确认密码';
$lang['api_customer_confirm_password_help']			= '如果匹配密码字段';
$lang['api_customer_company_contact_mobile']		= '手机号';
$lang['api_customer_company_contact_person']		= '联系人';
$lang['api_customer_company_contact_person_help']	= '请输入联系人的名字';
$lang['api_customer_offers']						= '我想收到消息和特别优惠';
$lang['api_customer_tc']							= '我同意 <a href="#" data-toggle="modal" data-target="#myModal"> 条款 </a>';
$lang['api_customer_req']							= '注册';
$lang['api_customer_thanks']						= '谢谢您注册！';
$lang['api_customer_names']							= '客户';
$lang['api_no_customer']							= '没有客户呢。';

//TLSTEC Terms & Conditions
$lang['tc']       = '<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">Terms & Conditions</h4>
					</div>
					<div class="modal-body custom-scroll terms-body">		
 <div id="left">
            <h1>TLSTEC TERMS & CONDITIONS</h1>
            <h2>Introduction</h2>
            <p>These terms and conditions govern your use of this website; by using this website, you accept these terms and conditions in full.   If you disagree with these terms and conditions or any part of these terms and conditions, you must not use this website.</p>
            <p>[You must be at least [18] years of age to use this website.  By using this website [and by agreeing to these terms and conditions] you warrant and represent that you are at least [18] years of age.]</p>
            <h2>License to use website</h2>
            <p>Unless otherwise stated, [NAME] and/or its licensors own the intellectual property rights in the website and material on the website.  Subject to the license below, all these intellectual property rights are reserved.</p>

            <p>You may view, download for caching purposes only, and print pages [or [OTHER CONTENT]] from the website for your own personal use, subject to the restrictions set out below and elsewhere in these terms and conditions.</p>

            <p>You must not:</p>
            <ul>
                <li>republish material from this website (including republication on another website);</li>
                <li>sell, rent or sub-license material from the website;</li>
                <li>show any material from the website in public;</li>
                <li>reproduce, duplicate, copy or otherwise exploit material on this website for a commercial purpose;]</li>
                <li>[edit or otherwise modify any material on the website; or]</li>
                <li>[redistribute material from this website [except for content specifically and expressly made available for redistribution].]</li>
            </ul>
            <p>[Where content is specifically made available for redistribution, it may only be redistributed [within your organisation].]</p>

            <h2>Acceptable use</h2>

            <p>You must not use this website in any way that causes, or may cause, damage to the website or impairment of the availability or accessibility of the website; or in any way which is unlawful, illegal, fraudulent or harmful, or in connection with any unlawful, illegal, fraudulent or harmful purpose or activity.</p>

            <p>You must not use this website to copy, store, host, transmit, send, use, publish or distribute any material which consists of (or is linked to) any spyware, computer virus, Trojan horse, worm, keystroke logger, rootkit or other malicious computer software.</p>

            <p>You must not conduct any systematic or automated data collection activities (including without limitation scraping, data mining, data extraction and data harvesting) on or in relation to this website without [NAME\'S] express written consent.</p>

            <p>[You must not use this website to transmit or send unsolicited commercial communications.]</p>

            <p>[You must not use this website for any purposes related to marketing without [NAME\'S] express written consent.]</p>

            <h2>[Restricted access</h2>

            <p>[Access to certain areas of this website is restricted.]  [NAME] reserves the right to restrict access to [other] areas of this website, or indeed this entire website, at [NAME\'S] discretion.</p>

            <p>If [NAME] provides you with a user ID and password to enable you to access restricted areas of this website or other content or services, you must ensure that the user ID and password are kept confidential.</p>

            <p>[[NAME] may disable your user ID and password in [NAME\'S] sole discretion without notice or explanation.]</p>

            <h2>[User content</h2>

            <p>In these terms and conditions, â€œyour user contentâ€� means material (including without limitation text, images, audio material, video material and audio-visual material) that you submit to this website, for whatever purpose.</p>

            <p>You grant to [NAME] a worldwide, irrevocable, non-exclusive, royalty-free license to use, reproduce, adapt, publish, translate and distribute your user content in any existing or future media.  You also grant to [NAME] the right to sub-license these rights, and the right to bring an action for infringement of these rights.</p>

            <p>Your user content must not be illegal or unlawful, must not infringe any third party\'s legal rights, and must not be capable of giving rise to legal action whether against you or [NAME] or a third party (in each case under any applicable law).</p>

            <p>You must not submit any user content to the website that is or has ever been the subject of any threatened or actual legal proceedings or other similar complaint.</p>

            <p>[NAME] reserves the right to edit or remove any material submitted to this website, or stored on [NAME\'S] servers, or hosted or published upon this website.</p>

            <p>[Notwithstanding [NAME\'S] rights under these terms and conditions in relation to user content, [NAME] does not undertake to monitor the submission of such content to, or the publication of such content on, this website.]</p>

            <h2>No warranties</h2>

            <p>This website is provided â€œas isâ€� without any representations or warranties, express or implied.  [NAME] makes no representations or warranties in relation to this website or the information and materials provided on this website.</p>

            <p>Without prejudice to the generality of the foregoing paragraph, [NAME] does not warrant that:</p>
            <ul>
                <li>this website will be constantly available, or available at all; or</li>
                <li>the information on this website is complete, true, accurate or non-misleading.</li>
            </ul>
            <p>Nothing on this website constitutes, or is meant to constitute, advice of any kind.  [If you require advice in relation to any [legal, financial or medical] matter you should consult an appropriate professional.]</p>

            <h2>Limitations of liability</h2>

            <p>[NAME] will not be liable to you (whether under the law of contact, the law of torts or otherwise) in relation to the contents of, or use of, or otherwise in connection with, this website:</p>
            <ul>
                <li>[to the extent that the website is provided free-of-charge, for any direct loss;]</li>
                <li>for any indirect, special or consequential loss; or</li>
                <li>for any business losses, loss of revenue, income, profits or anticipated savings, loss of contracts or business relationships, loss of reputation or goodwill, or loss or corruption of information or data.</li>
            </ul>
            <p>These limitations of liability apply even if [NAME] has been expressly advised of the potential loss.</p>

            <h2>Exceptions</h2>

            <p>Nothing in this website disclaimer will exclude or limit any warranty implied by law that it would be unlawful to exclude or limit; and nothing in this website disclaimer will exclude or limit [NAME\'S] liability in respect of any:</p>
            <ul>
                <li>death or personal injury caused by [NAME\'S] negligence;</li>
                <li>fraud or fraudulent misrepresentation on the part of [NAME]; or</li>
                <li>matter which it would be illegal or unlawful for [NAME] to exclude or limit, or to attempt or purport to exclude or limit, its liability.</li>
            </ul>
            <h2>Reasonableness</h2>

            <p>By using this website, you agree that the exclusions and limitations of liability set out in this website disclaimer are reasonable.</p>

            <p>If you do not think they are reasonable, you must not use this website.</p>

            <h2>Other parties</h2>

            <p>[You accept that, as a limited liability entity, [NAME] has an interest in limiting the personal liability of its officers and employees.  You agree that you will not bring any claim personally against [NAME\'S] officers or employees in respect of any losses you suffer in connection with the website.]</p>

            <p>[Without prejudice to the foregoing paragraph,] you agree that the limitations of warranties and liability set out in this website disclaimer will protect [NAME\'S] officers, employees, agents, subsidiaries, successors, assigns and sub-contractors as well as [NAME].</p>

            <h2>Unenforceable provisions</h2>

            <p>If any provision of this website disclaimer is, or is found to be, unenforceable under applicable law, that will not affect the enforceability of the other provisions of this website disclaimer.</p>

            <h2>Indemnity</h2>

            <p>You hereby indemnify [NAME] and undertake to keep [NAME] indemnified against any losses, damages, costs, liabilities and expenses (including without limitation legal expenses and any amounts paid by [NAME] to a third party in settlement of a claim or dispute on the advice of [NAME\'S] legal advisers) incurred or suffered by [NAME] arising out of any breach by you of any provision of these terms and conditions[, or arising out of any claim that you have breached any provision of these terms and conditions].</p>

            <h2>Breaches of these terms and conditions</h2>

            <p>Without prejudice to [NAME\'S] other rights under these terms and conditions, if you breach these terms and conditions in any way, [NAME] may take such action as [NAME] deems appropriate to deal with the breach, including suspending your access to the website, prohibiting you from accessing the website, blocking computers using your IP address from accessing the website, contacting your internet service provider to request that they block your access to the website and/or bringing court proceedings against you.</p>

            <h2>Variation</h2>

            <p>[NAME] may revise these terms and conditions from time-to-time.  Revised terms and conditions will apply to the use of this website from the date of the publication of the revised terms and conditions on this website.  Please check this page regularly to ensure you are familiar with the current version.</p>

            <h2>Assignment</h2>

            <p>[NAME] may transfer, sub-contract or otherwise deal with [NAME\'S] rights and/or obligations under these terms and conditions without notifying you or obtaining your consent.</p>

            <p>You may not transfer, sub-contract or otherwise deal with your rights and/or obligations under these terms and conditions.</p>

            <h2>Severability</h2>

            <p>If a provision of these terms and conditions is determined by any court or other competent authority to be unlawful and/or unenforceable, the other provisions will continue in effect.  If any unlawful and/or unenforceable provision would be lawful or enforceable if part of it were deleted, that part will be deemed to be deleted, and the rest of the provision will continue in effect.</p>

            <h2>Entire agreement</h2>

            <p>These terms and conditions [, together with [DOCUMENTS],] constitute the entire agreement between you and [NAME] in relation to your use of this website, and supersede all previous agreements in respect of your use of this website.</p>

            <h2>Law and jurisdiction</h2>

            <p>These terms and conditions will be governed by and construed in accordance with [GOVERNING LAW], and any disputes relating to these terms and conditions will be subject to the [non-]exclusive jurisdiction of the courts of [JURISDICTION].</p>

<h2>About these website terms and conditions</h2><p>We created these website terms and conditions with the help of a free website terms and conditions form developed by Contractology and available at <a href="http://www.SmartAdmin.com">www.SmartAdmin.com</a>.
Contractology supply a wide variety of commercial legal documents, such as <a href="#">template data protection statements</a>.
</p>
            <h2>[Registrations and authorisations</h2>

            <p>[[NAME] is registered with [TRADE REGISTER].  You can find the online version of the register at [URL].  [NAME\'S] registration number is [NUMBER].]</p>

            <p>[[NAME] is subject to [AUTHORISATION SCHEME], which is supervised by [SUPERVISORY AUTHORITY].]</p>

            <p>[[NAME] is registered with [PROFESSIONAL BODY].  [NAME\'S] professional title is [TITLE] and it has been granted in [JURISDICTION].  [NAME] is subject to the [RULES] which can be found at [URL].]</p>

            <p>[[NAME] subscribes to the following code[s] of conduct: [CODE(S) OF CONDUCT].  [These codes/this code] can be consulted electronically at [URL(S)].</p>

            <p>[[NAME\'S] [TAX] number is [NUMBER].]]</p>

            <h2>[NAME\'S] details</h2>

            <p>The full name of [NAME] is [FULL NAME].</p>

            <p>[[NAME] is registered in [JURISDICTION] under registration number [NUMBER].]</p>

            <p>[NAME\'S] [registered] address is [ADDRESS].</p>

            <p>You can contact [NAME] by email to [EMAIL].</p>

           

            </div>
			
			<br><br>

            <p><strong>By using this  WEBSITE TERMS AND CONDITIONS template document, you agree to the 
	 <a href="#">terms and conditions</a> set out on 
	 <a href="#">tlstec.com</a>.  You must retain the credit 
	 set out in the section headed "ABOUT THESE WEBSITE TERMS AND CONDITIONS".  Subject to the licensing restrictions, you should 
	 edit the document, adapting it to the requirements of your jurisdiction, your business and your 
	 website.  If you are not a lawyer, we recommend that you take professional legal advice in relation to the editing and 
	 use of the template.</strong></p>


					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Cancel
						</button>
						<button type="button" class="btn btn-primary" id="i-agree">
							<i class="fa fa-check"></i> I Agree
						</button>
						
						<button type="button" class="btn btn-danger pull-left" id="print">
							<i class="fa fa-print"></i> Print
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->';



// UsersInfo
$lang['index_heading']           	= 'Users and Groups';
$lang['index_subheading']        	= 'Below is a list of the users.';
$lang['index_fname_th']          	= 'First Name';
$lang['index_lname_th']          	= 'Last Name';
$lang['index_email_th']          	= 'Email';
$lang['index_user_th']          	= 'Username';
$lang['index_groups_th']         	= 'Groups';
$lang['index_status_th']         	= 'Status';
$lang['index_action_th']         	= 'Action';
$lang['index_active_link']       	= 'Active';
$lang['index_inactive_link']     	= 'Inactive';
$lang['index_create_user_link']  	= 'Create a new user';
$lang['index_create_group_link'] 	= 'Create a new group';
$lang['index_design_template_link']	= 'Design Template';
$lang['index_change_password_link'] = 'Change Password';
$lang['index_design_template_link'] = 'Design Template';
$lang['index_logout_link']			= 'Logout';
$lang['index_scaffold_link']	    = 'Scaffolding Main Page';
$lang['index_workflow_link']        = 'Workflow Main Page';
$lang['index_app_th']          	= 'Applications Created';
$lang['index_app']          	= 'Applications';
$lang['index_app_created']          	= 'Created on';
$lang['index_app_des'] = 'App Description';
$lang['index_app_delete'] = 'Delete App';
$lang['index_app_edit'] = 'Edit App';
$lang['index_app_use']  = 'Use App';
$lang['index_app_share'] = 'Share App';
$lang['index_app_unshare'] = 'Unshare App';

// Deactivate User
$lang['deactivate_user_nav']				 = 'Deactivate User';
$lang['deactivate_heading']                  = 'Deactivate User';
$lang['deactivate_subheading']               = 'Are you sure you want to deactivate the user \'%s\'';
$lang['deactivate_confirm_y_label']          = 'Yes:';
$lang['deactivate_confirm_n_label']          = 'No:';
$lang['deactivate_submit_btn']               = 'Submit';
$lang['deactivate_validation_confirm_label'] = 'confirmation';
$lang['deactivate_validation_user_id_label'] = 'user ID';

// Create User
$lang['create_user_nav']							   = 'Create User';
$lang['create_user_heading']                           = 'Create User';
$lang['create_user_subheading']                        = 'Please enter the users information below.';
$lang['create_user_fname_label']                       = 'First Name:';
$lang['create_user_lname_label']                       = 'Last Name:';
$lang['create_user_company_label']                     = 'Company Name:';
$lang['create_user_email_label']                       = 'Email:';
$lang['create_user_phone_label']                       = 'Phone:';
$lang['create_user_password_label']                    = 'Password:';
$lang['create_user_password_confirm_label']            = 'Confirm Password:';
$lang['create_user_submit_btn']                        = 'Create User';
$lang['create_user_validation_fname_label']            = 'First Name';
$lang['create_user_validation_lname_label']            = 'Last Name';
$lang['create_user_validation_email_label']            = 'Email Address';
$lang['create_user_validation_phone1_label']           = 'First Part of Phone';
$lang['create_user_validation_phone2_label']           = 'Second Part of Phone';
$lang['create_user_validation_phone3_label']           = 'Third Part of Phone';
$lang['create_user_validation_company_label']          = 'Company Name';
$lang['create_user_validation_password_label']         = 'Password';
$lang['create_user_validation_password_confirm_label'] = 'Password Confirmation';

// Edit User
$lang['edit_user_nav']							   =	'Edit User';
$lang['edit_user_heading']                           = 'Edit User';
$lang['edit_user_subheading']                        = 'Please enter the users information below.';
$lang['edit_user_fname_label']                       = 'First Name:';
$lang['edit_user_lname_label']                       = 'Last Name:';
$lang['edit_user_company_label']                     = 'Company Name:';
$lang['edit_user_email_label']                       = 'Email:';
$lang['edit_user_phone_label']                       = 'Phone:';
$lang['edit_user_password_label']                    = 'Password: (if changing password)';
$lang['edit_user_password_confirm_label']            = 'Confirm Password: (if changing password)';
$lang['edit_user_groups_heading']                    = 'Member of groups';
$lang['edit_user_submit_btn']                        = 'Save User';
$lang['edit_user_validation_fname_label']            = 'First Name';
$lang['edit_user_validation_lname_label']            = 'Last Name';
$lang['edit_user_validation_email_label']            = 'Email Address';
$lang['edit_user_validation_phone1_label']           = 'First Part of Phone';
$lang['edit_user_validation_phone2_label']           = 'Second Part of Phone';
$lang['edit_user_validation_phone3_label']           = 'Third Part of Phone';
$lang['edit_user_validation_company_label']          = 'Company Name';
$lang['edit_user_validation_groups_label']           = 'Groups';
$lang['edit_user_validation_password_label']         = 'Password';
$lang['edit_user_validation_password_confirm_label'] = 'Password Confirmation';

// Register user
$lang['register_user_fname_label']                       = 'First Name:';
$lang['register_user_lname_label']                       = 'Last Name:';
$lang['register_user_company_label']                     = 'Company Name:';
$lang['register_user_email_label']                       = 'Email:';
$lang['register_user_phone_label']                       = 'Phone:';
$lang['register_user_password_label']                    = 'Password:';
$lang['register_user_password_confirm_label']            = 'Confirm Password:';
$lang['register_user_subscribed_companies']              = 'Subscribed Under';
$lang['register_user_subscribed_plan']                   = 'Subscribed Plan';
$lang['register_user_groups']                            = 'Groups';
$lang['reg_completed']                                   = ' Your registration completed ! <br><br> &nbsp;&nbsp;&nbsp; Start using your device ! ! ';

// Create Group
$lang['create_group_nav']					 =	'Create Group';
$lang['create_group_title']                  =	'Create Group';
$lang['create_group_heading']                =	'Create Group';
$lang['create_group_subheading']             =	'Please enter the group information below.';
$lang['create_group_name_label']             =	'Group Name:';
$lang['create_group_desc_label']             =	'Description:';
$lang['create_group_submit_btn']             =	'Create Group';
$lang['create_group_validation_name_label']  =	'Group Name';
$lang['create_group_validation_desc_label']  =	'Description';

// Edit Group
$lang['edit_group_nav']					 =	'Edit Group';
$lang['edit_group_title']                  = 'Edit Group';
$lang['edit_group_saved']                  = 'Group Saved';
$lang['edit_group_heading']                = 'Edit Group';
$lang['edit_group_subheading']             = 'Please enter the group information below.';
$lang['edit_group_name_label']             = 'Group Name:';
$lang['edit_group_desc_label']             = 'Description:';
$lang['edit_group_submit_btn']             = 'Save Group';
$lang['edit_group_validation_name_label']  = 'Group Name';
$lang['edit_group_validation_desc_label']  = 'Description';

// Change Password
$lang['change_password_nav']						   		   =	'Change Password';
$lang['change_password_heading']                               =	'Change Password';
$lang['change_password_old_password_label']                    =	'Old Password:';
$lang['change_password_new_password_label']                    =	'New Password (at least %s characters long):';
$lang['change_password_new_password_confirm_label']            =	'Confirm New Password:';
$lang['change_password_submit_btn']                            =	'Change';
$lang['change_password_validation_old_password_label']         =	'Old Password';
$lang['change_password_validation_new_password_label']         =	'New Password';
$lang['change_password_validation_new_password_confirm_label'] =	'Confirm New Password';

// Forgot Password
$lang['forgot_password_heading']                 = 'Forgot Password';
$lang['forgot_password_subheading']              = 'Please enter your %s so we can send you an email to reset your password.';
$lang['forgot_password_email_label']             = '%s:';
$lang['forgot_password_submit_btn']              = 'Submit';
$lang['forgot_password_validation_email_label']  = 'Email Address';
$lang['forgot_password_username_identity_label'] = 'Username';
$lang['forgot_password_email_identity_label']    = 'Email';
$lang['forgot_password_email_not_found']         = 'No record of that email address.';

// Reset Password
$lang['reset_password_heading']                               = 'Change Password';
$lang['reset_password_new_password_label']                    = 'New Password (at least %s characters long):';
$lang['reset_password_new_password_confirm_label']            = 'Confirm New Password:';
$lang['reset_password_submit_btn']                            = 'Change';
$lang['reset_password_validation_new_password_label']         = 'New Password';
$lang['reset_password_validation_new_password_confirm_label'] = 'Confirm New Password';

// Activation Email
$lang['email_activate_heading']    = 'Activate account for %s';
$lang['email_activate_subheading'] = 'Please click this link to %s.';
$lang['email_activate_link']       = 'Activate Your Account';

// Forgot Password Email
$lang['email_forgot_password_heading']    = 'Reset Password for %s';
$lang['email_forgot_password_subheading'] = 'Please click this link to %s.';
$lang['email_forgot_password_link']       = 'Reset Your Password';

// New Password Email
$lang['email_new_password_heading']    = 'New Password for %s';
$lang['email_new_password_subheading'] = 'Your password has been reset to: %s';

// Template
$lang['template_nav']			  =	'Design Template';
$lang['app_prop_heading']		 =	'App Properties';
$lang['prop_sub_heading']	  =	'Define App Properties';
$lang['app_description']	  =	'Description:';
$lang['app_type']	  =	'App Type:';
$lang['app_expiry']	  =	'App Expiry Date:';
$lang['app_category']	  =	'App Category:';
$lang['app_edit'] = 'Edit';
$lang['app_use'] = 'Use';
$lang['app_delete'] = 'Delete';
$lang['app_share'] = 'Share';
$lang['app_unshare'] = 'Unshare';
$lang['prop_ok_btn']	  =	'Start Creating App';
$lang['notification_nav']			  =	'Notification';
$lang['notification_heading']			      =	'User Notification';
$lang['notification_ok_btn']	  =	'Send Notification';
$lang['template_heading']         =	'Template Development Application';
$lang['template_sub_heading']	  =	'Application';
$lang['template_add_field_btn']	  =	'Add.Element';
$lang['template_move_btn']	  =	'Move on to workflow';
$lang['template_app_name']	  =	'Application Name:';
$lang['template_element_count']	  =	'Elements Remaining:';





// Scaffold
$lang['scaffold_heading']         = 'Output of Tamplate Application';

// Workflow
$lang['workflow_nav']			   	    =	'Workflow Tool';
$lang['workflow_heading']           	= 'Workflow Application';
$lang['workflow_subheading']        	= 'Below is a list of the tamplates';
$lang['workflow_create_user_link']  	= 'Create a new user';
$lang['workflow_create_group_link'] 	= 'Create a new group';
$lang['workflow_design_template_link']	= 'Design Template';
$lang['workflow_change_password_link']  = 'Change Password';
$lang['workflow_design_template_link']  = 'Design Template';
$lang['workflow_logout_link']			= 'Logout';
$lang['workflow_scaffold_link']	        = 'Scaffolding Main Page';

// User
$lang['user_heading']           	= 'User Inbox';
$lang['user_subheading']        	= 'Below is a list of the pending tamplates';
$lang['user_change_password_link']  = 'Change Password';
$lang['user_logout_link']			= 'Logout';

// Admin dash
$lang['admin_dash_list_apps']		=	'List of Applications';
$lang['admin_dash_list_doc']		=	'List of Documents';
$lang['admin_dash_list_users']		=	'Customers';
$lang['admin_activities']    =  'Activities';
$lang['admin_dash_nav']				=	'Dashboard';
$lang['admin_dash_subheading']        	= 'Below is a list of the users.';
$lang['index_create_user_link']  	= 'Create a new user';
$lang['index_create_group_link'] 	= 'Create a new group';
$lang['index_design_template_link']	= 'Design Template';
$lang['index_change_password_link'] = 'Change Password';
$lang['index_design_template_link'] = 'Design Template';
$lang['index_logout_link']			= 'Logout';
$lang['index_scaffold_link']	    = 'Scaffolding Main Page';
$lang['index_workflow_link']        = 'Workflow Main Page';
$lang['admin_dash_home_link']       = 'Home';
$lang['admin_no_apps']       = 'There is no application created yet.';
$lang['admin_no_docs']       = 'There is no document for any application yet.';
$lang['admin_no_apps_created'] = 'There is no application created by you';
$lang['delete_confirm'] = 'Do you really want to continue ?';

//TLSTEC Admin dash
$lang['customer_company_name']           	= 'Company Name ';
$lang['customer_company_address']        	= 'Address';
$lang['customer_company_website']		    = 'Website';
$lang['customer_company_contact_person']	= 'Contact Person';
$lang['customer_company_contact_email']		= 'Contact Email';
$lang['customer_company_contact_mobile']	= 'Contact Number';
$lang['customer_plan']						= 'Plan';
$lang['customer_username'] 					= 'Username ';
$lang['customer_password'] 					= 'Password ';
$lang['customer_confirm_password'] 			= 'Confirm Password ';
$lang['customer_plan_expiry']				= 'Plan Expiry';
$lang['customer_plan_registered_on']		= 'Plan Registered on';
$lang['customer_plan_status']				= 'Plan Status';
$lang['customer_plan_status_active']		= 'Active';
$lang['customer_plan_status_deactive']		= 'Deactive';
$lang['customer_action_th']					= 'Action';
$lang['customer_deactivate_link']			= '<button class="btn btn-warning btn-xs">Deactivate</button>';
$lang['customer_activate_link']				= '<button class="btn btn-warning btn-xs">Activate</button>';
$lang['customer_empty']						= 'No customers!';
$lang['customer_usage_link']				= '<button class="btn bg-color-green txt-color-white btn-xs">Check usage</button>';
$lang['usage_of_company']					= 'PAAS usage of %s is as follows:';
$lang['usage_api']							= 'Third Party Subscriptions';
$lang['usage_app']							= 'Applications';
$lang['usage_doc']							= 'Document Submissions';
$lang['usage_total_users']					= 'Total Users';
$lang['usage_on_wf']						= 'Ongoing Workflows';
$lang['usage_off_wf']						= 'Finished Workflows';
$lang['usage_disk']							= 'Disk Space(GB)';


// TLSTEC SUPPORT ADMIN
$lang['support_admin_first_name']           = 'First Name';
$lang['support_admin_last_name']            = 'Last Name';
$lang['support_admin_username'] 		    = 'Username ';
$lang['support_admin_email'] 		        = 'Email ID';
$lang['support_admin_mobile'] 		        = 'Mobile ';
$lang['support_admin_level'] 		        = 'Level ';
$lang['support_admin_status'] 		        = 'Status ';
$lang['support_admin_empty']                = 'There is no support admin created yet.';
$lang['admin_dash_list_support_admin']      = 'Support Admin';
$lang['support_admin_edit_index'] 		    = 'Edit';
$lang['support_admin_edit']                 =  '<button class="btn bg-color-green txt-color-white btn-xs">Edit</button>';
$lang['support_admin_delete']               =  '<button class="btn bg-color-red txt-color-white btn-xs">Delete</button>';
$lang['support_admin_delete_index'] 		= 'Delete';
$lang['support_admin_activate'] 		    = 'Activate';
$lang['support_admin_active']               = 'Active';
$lang['support_admin_inactive']             = 'Inactive';

/* CREATE TLSTEC SUPPORT ADMIN */
$lang['create_support_admin_first_name']        = 'First Name:';
$lang['create_support_admin_last_name']         = 'Last Name:';
$lang['create_support_admin_company_name'] 	    = 'Company: ';
$lang['create_support_admin_email'] 		    = 'Email:';
$lang['create_support_admin_phone'] 		    = 'Phone: ';
$lang['create_support_admin_password'] 		    = 'Password:';
$lang['create_support_admin_confirm_password']  = 'Confirm Password:';
$lang['create_support_admin_level']             = 'Level';
$lang['create_support_admin_subheading']        = 'Please enter the support admin information below.';
$lang['create_support_admin_submit_btn']        = 'Create Support Admin';

/* EDIT TLSTEC SUPPORT ADMIN */
$lang['edit_support_admin_first_name']        = 'First Name:';
$lang['edit_support_admin_last_name']         = 'Last Name:';
$lang['edit_support_admin_company_name'] 	  = 'Company: ';
$lang['edit_support_admin_email'] 		      = 'Email:';
$lang['edit_support_admin_phone'] 		      = 'Phone: ';
$lang['edit_support_admin_password'] 		  = 'Password:';
$lang['edit_support_admin_confirm_password']  = 'Confirm Password:';
$lang['edit_support_admin_level']             = 'Level';
$lang['edit_support_admin_subheading']        = 'Please enter the support admin information below.';
$lang['edit_support_admin_submit_btn']        = 'Save Support Admin';
$lang['edit_support_admin_heading']           = 'Edit Admin';

/* ACTIVATE OR DE-ACTIVATE SUPPORT ADMIN */
$lang['support_deactivate_heading']           = 'Deactivate Support Admin';
$lang['support_deactivate_subheading']        = 'Are you sure you want to deactivate the support admin \'%s\'';
$lang['support_activate_link']                = '<button class="btn btn-warning btn-xs">Activate</button>';
$lang['support_deactivate_link']       	      =  '<button class="btn btn-warning btn-xs">Deactivate</button>';

/* CRASH LOGs IN DETAIL */
$lang['log_device_uniq_no']                  = 'Device Unique Number';
$lang['log_crashed_app']                     = 'Crashed App';
$lang['log_received_time']                   = 'Received On';
$lang['log_received_feedback']               = 'User Feedback';
$lang['log_files_list']                      = 'Log Files';
$lang['log_files_delete']                    = 'Delete';
$lang['no_detail_logs']                      = '<pre class="log-empty"><div class="log-info">No Logs</div></pre>';

/* TICKET MANAGEMENT */
$lang['create_ticket_heading']               = 'Create Ticket';
$lang['create_ticket_device_unique_num']     = 'Device Unique Number';
$lang['create_ticket_user_email']            = 'Subscribed User Email';
$lang['create_ticket_crashed_app']           = 'Crashed App';
$lang['create_ticket_description']           = 'Problem Description';
$lang['ticket_device_uniq_no_label']         = 'Device Unique Number';
$lang['ticket_service_req_no_label']         = 'Service Request Number';
$lang['ticket_crashed_app_label']            = 'Crashed App';
$lang['ticket_registered_time_label']        = 'Registered On';
$lang['ticket_owner_label']                  = 'Ticket Owner';
$lang['ticket_manage_label']                 = 'Manage';
$lang['ticket_manage']                       = '<button class="btn bg-color-green txt-color-white btn-xs manage">Manage</button>';
$lang['ticket_des_label']                    = 'Description';
$lang['no_tickets']                          = 'No Tickets';
$lang['ticket_attachment_label']             = 'Attachments';


$lang['admin_no_customers']       = 'There is no customer registered yet.';
$lang['create_admin_nav']							   =	'Create Admin';
$lang['create_admin_heading']                           = 'Create Admin';
$lang['create_admin_subheading']                        = 'Please enter the admin information below.';
$lang['create_admin_submit_btn']                        = 'Create Admin';

//Change password rules & messages
$lang['new_password_check']     = '\'New password should be atleast 8 characters\'';
$lang['confirm_password_check'] = '\'Confirm password should be atleast 8 characters\'';
$lang['confirm_password_match_check'] = '\'Confirm password should match new password\'';
