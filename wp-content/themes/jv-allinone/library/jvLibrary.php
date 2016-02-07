<?php
/*
 # JV Library - JV Theme
 # ------------------------------------------------------------------------
 # author    Open Source Code Solutions Co
 # copyright Copyright (C) 2011 joomlavi.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL or later.
 # Websites: http://www.joomlavi.com
 # Technical Support:  http://www.joomlavi.com/my-tickets.html
-------------------------------------------------------------------------*/

/**
 * JV Allinone functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API 
 * 
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/
get_template_part( 'library/Mobile_Detect' ); 


class JVLibrary{
     
    static $jvStyle = array();
    static $config = array();
    static $cssInline = array();
    static $jsInline = array();
    static $body = array();
    static $paths = array();
    static $urls = array();
     
    public static function getConfig($more = true){
         if(!self::$config){
             self::$config = (object) get_option(JVLibrary::getKey().'_theme_settings');
             if(!isset(self::$config->logo)) self::$config = self::defaultConfig ();
             if($more){
                self::$config->sitename = esc_attr( get_bloginfo( 'name', 'display' ) );
                self::$config->siteurl = esc_url(  home_url("/") );
				self::$config->logourl = esc_url( get_site_url() );
                self::$config->template = get_template();
             }
         }
         return self::$config;
     }
    
    public static function import($file, $folder = null){
        if(!$folder) include_once( $file );
    }
    public static function defaultConfig(){
        $config = 'YToyNTp7czo0OiJsb2dvIjthOjU6e3M6NDoidHlwZSI7czo1OiJpbWFnZSI7czo2OiJub3JtYWwiO3M6MDoiIjtzOjc6InNwZWNpYWwiO3M6MDoiIjtzOjQ6InRleHQiO3M6ODoiQUxMSU5PTkUiO3M6Njoic2xvZ2FuIjtzOjA6IiI7fXM6NToiaXNSVEwiO3M6NDoiYXV0byI7czo3OiJnb3RvdG9wIjtzOjE6IjEiO3M6MTE6Imdvb2dsZWZvbnRzIjtzOjEzMjQ6Ilt7ImVuYWJsZSI6dHJ1ZSwiZWxlbWVudCI6IiNuYXYtbWFpbm1lbnUgIGEiLCJ0aXRsZSI6Ik1lbnUgLSBPc3dhbGQiLCJmYW1pbHkiOiIiLCJuYW1lIjoiT3N3YWxkIiwid2VpZ2h0IjoiMzAwLHJlZ3VsYXIsNzAwIn0seyJlbmFibGUiOnRydWUsImVsZW1lbnQiOiJib2R5LGgyLndpZGdldHRpdGxlIC5zdWItdGl0bGUiLCJ0aXRsZSI6IkJvZHkgLSBPcGVuIFNhbnMiLCJmYW1pbHkiOiIiLCJuYW1lIjoiT3BlbiBTYW5zIiwid2VpZ2h0IjoiMzAwLHJlZ3VsYXIsaXRhbGljIn0seyJlbmFibGUiOnRydWUsImVsZW1lbnQiOiJoMSxoMixoMyxoNCxoNSxoNiwud3BiX2FjY29yZGlvbiAud3BiX2FjY29yZGlvbl93cmFwcGVyIC53cGJfYWNjb3JkaW9uX2hlYWRlciwud3BiX3RhYnNfZml4IC53cGJfY29udGVudF9lbGVtZW50IC53cGJfdGFic19uYXYgbGkgYSwuZGF0ZUl0ZW0sdWwubWVnYS1tZW51IGxpW2NsYXNzKj1cIm1lZ2EtbWVudS1jb2x1bW5zLTFcIl0gPiBhLC5wcm9kdWN0LXByaWNlIC5wcmljZSxmb3JtLndvb2NvbW1lcmNlLXByb2R1Y3Qtc2VhcmNoIGlucHV0W3R5cGU9XCJzdWJtaXRcIl0sLmJ0biwud3BjZjctZm9ybSBpbnB1dFt0eXBlPVwic3VibWl0XCJdLCNjb21tZW50Zm9ybSAuc3VibWl0LCNCb3R0b20gLndpZGdldF9yZWNlbnRfZW50cmllcyBhLC5udW1iZXJzLWljbywuc3ViY2F0ZWdvcmllcyBhLC5wYWdpbmF0aW9uICAucGFnZS1udW1iZXJzLCNwYWdlLXNob3Atc2lkZWJhciAjY29udGVudCB1bC50YWJzIGxpIGEsYS5sb2dvLXRleHQgIC50ZXh0LC5wYWdpbmF0aW9uLC5wYWdlLW51bWJlcnMsI3BhZ2UgI2J1ZGR5cHJlc3MgaW5wdXRbdHlwZT1cInN1Ym1pdFwiXSwjcGFnZSAjYnVkZHlwcmVzcyBpbnB1dFt0eXBlPVwiYnV0dG9uXCJdLCNwYWdlICNidWRkeXByZXNzIGRpdi5pdGVtLWxpc3QtdGFicyB1bCBsaSA+IGEsI3BhZ2UgI2J1ZGR5cHJlc3MgZGl2Lml0ZW0tbGlzdC10YWJzIHVsIGxpID4gc3Bhbiwud3BiX3RhYnNfZml4IHVsLnZjX3R0YS10YWJzLWxpc3QgbGkgYSIsInRpdGxlIjoiSGVhZGluZyAtIE9zd2FsZCIsImZhbWlseSI6IiIsIm5hbWUiOiJPc3dhbGQiLCJ3ZWlnaHQiOiIzMDAscmVndWxhciw3MDAifSx7ImVuYWJsZSI6dHJ1ZSwiZWxlbWVudCI6IiNuYXYtbWFpbm1lbnUgdWwubWVnYS1tZW51IGxpW2NsYXNzKj1cIm1lZ2EtbWVudS1jb2x1bW5zLTFcIl0gPiAubWVnYS1zdWItbWVudSA+IGxpID4gYSwjbmF2LW1haW5tZW51ICAuc3VibWVudS1jYXRlZ29yeSAgYSIsInRpdGxlIjoiU3VibWVudSIsImZhbWlseSI6IiIsIm5hbWUiOiJPcGVuIFNhbnMiLCJ3ZWlnaHQiOiIzMDAifV0iO3M6MTE6InN0eWxlbGF5b3V0IjtzOjQ6IndpZGUiO3M6ODoiYmdfY29sb3IiO3M6MDoiIjtzOjExOiJiZ19wb3NpdGlvbiI7czo3OiJpbmhlcml0IjtzOjEzOiJiZ19hdHRhY2htZW50IjtzOjc6ImluaGVyaXQiO3M6OToiYmdfcmVwZWF0IjtzOjc6ImluaGVyaXQiO3M6MTA6ImJhY2tncm91bmQiO3M6MDoiIjtzOjEwOiJ0aGVtZWNvbG9yIjtzOjc6ImNvbG9yLTEiO3M6OToic2hvd19kZW1vIjtzOjI6Im5vIjtzOjc6InZlcnNpb24iO3M6MzoiMS4zIjtzOjU6ImpzT3dsIjtzOjI3ODU6Ilt7ImVuYWJsZSI6dHJ1ZSwiZWxlbWVudCI6Ii53dGVzdGltb25pYWxzLnNsaWRlciAuanYtdGVzdGltb25pYWxzLnNsaWRlci0zLWl0ZW1zIiwidGl0bGUiOiIzIGl0ZW1zIC0gbmF2aWdhdGlvbiAtIGF1dG8gaGVpZ2h0IiwicGFyYW1zIjoiaXRlbXMgOiAzXG5wYWdpbmF0aW9uOiBmYWxzZVxubmF2aWdhdGlvbiA6IHRydWVcbm5hdmlnYXRpb25UZXh0IDogW1wiPGkgY2xhc3M9J2ZhIGZhLWFuZ2xlLWxlZnQnPjwvaT5cIixcIjxpIGNsYXNzPSdmYSBmYS1hbmdsZS1yaWdodCc+PC9pPlwiXVxuYXV0b1BsYXkgOmZhbHNlXG5pdGVtc1RhYmxldDogWzc2OCwzXVxuaXRlbXNNb2JpbGU6IFs0NzksMV0ifSx7ImVuYWJsZSI6dHJ1ZSwiZWxlbWVudCI6Ii5zbGlkZXItMi1pdGVtcyAucnB3ZS1kaXYuc2xpZGVyIiwidGl0bGUiOiIyIGl0ZW1zIC0gbmF2aWdhdGlvbiIsInBhcmFtcyI6Iml0ZW1zIDogMlxucGFnaW5hdGlvbjogZmFsc2Vcbm5hdmlnYXRpb24gOiB0cnVlXG5uYXZpZ2F0aW9uVGV4dCA6IFtcIjxpIGNsYXNzPSdmYSBmYS1hbmdsZS1sZWZ0Jz48L2k+XCIsXCI8aSBjbGFzcz0nZmEgZmEtYW5nbGUtcmlnaHQnPjwvaT5cIl1cbmF1dG9QbGF5IDpmYWxzZVxuaXRlbXNEZXNrdG9wIDogWzExOTksMl1cbml0ZW1zRGVza3RvcFNtYWxsIDogWzk3OSwyXVxuaXRlbXNUYWJsZXQ6IFs3NjgsMl1cbml0ZW1zTW9iaWxlOiBbNDc5LDFdIn0seyJlbmFibGUiOnRydWUsImVsZW1lbnQiOiIjU0VPLWNsaWVudHMiLCJ0aXRsZSI6IlNFTy1DbGllbnRzLXNsaWRlciIsInBhcmFtcyI6Iml0ZW1zIDogNlxucGFnaW5hdGlvbjogZmFsc2Vcbm5hdmlnYXRpb24gOiB0cnVlXG5uYXZpZ2F0aW9uVGV4dCA6IFtcIjxpIGNsYXNzPSdmYSBmYS1hbmdsZS1sZWZ0Jz48L2k+XCIsXCI8aSBjbGFzcz0nZmEgZmEtYW5nbGUtcmlnaHQnPjwvaT5cIl1cbmF1dG9QbGF5IDpmYWxzZVxuaXRlbXNUYWJsZXQ6IFs3NjgsM11cbml0ZW1zTW9iaWxlOiBbNDc5LDJdIn0seyJlbmFibGUiOnRydWUsImVsZW1lbnQiOiIjU0VPLXRlc3RvbW9uaWFscywgLnNsaWRlci0zLWl0ZW1zIC5ycHdlLWRpdi5zbGlkZXIiLCJ0aXRsZSI6IlNFTy10ZXN0b21vbmlhbHMtc2xpZGVyIC0gMyBpdGVtcyAtIG5hdmlnYXRpb24iLCJwYXJhbXMiOiJpdGVtcyA6IDNcbnBhZ2luYXRpb246IGZhbHNlXG5uYXZpZ2F0aW9uIDogdHJ1ZVxubmF2aWdhdGlvblRleHQgOiBbXCI8aSBjbGFzcz0nZmEgZmEtYW5nbGUtbGVmdCc+PC9pPlwiLFwiPGkgY2xhc3M9J2ZhIGZhLWFuZ2xlLXJpZ2h0Jz48L2k+XCJdXG5hdXRvUGxheSA6ZmFsc2Vcbml0ZW1zVGFibGV0OiBbNzY4LDNdXG5pdGVtc01vYmlsZTogWzQ3OSwxXSJ9LHsiZW5hYmxlIjp0cnVlLCJlbGVtZW50IjoiLnNsaWRlci1zdWJjYXRlZ29yaWVzIiwidGl0bGUiOiJTdWIgQ2F0ZWdvcmllcyIsInBhcmFtcyI6Iml0ZW1zIDogNFxucGFnaW5hdGlvbjogZmFsc2Vcbm5hdmlnYXRpb24gOiBmYWxzZVxuYXV0b1BsYXkgOnRydWVcbml0ZW1zVGFibGV0OiBbNzY4LDNdXG5pdGVtc01vYmlsZTogWzQ3OSwyXSJ9LHsiZW5hYmxlIjp0cnVlLCJlbGVtZW50IjoiLnRodW1ibmFpbF9sYXJnZSAuYmxvZy1nZWxsYXJ5IiwidGl0bGUiOiJCbG9nIC0gR2VsbGFyeSIsInBhcmFtcyI6Im5hdmlnYXRpb24gOiBmYWxzZVxucGFnaW5hdGlvbjogZmFsc2VcbmF1dG9QbGF5IDogdHJ1ZVxuc2luZ2xlSXRlbSA6IHRydWVcdFx0XHRcdFxubmF2aWdhdGlvblRleHQgOiBbXCI8aSBjbGFzcz0nZmEgZmEtYW5nbGUtbGVmdCc+PC9pPlwiLFwiPGkgY2xhc3M9J2ZhIGZhLWFuZ2xlLXJpZ2h0Jz48L2k+XCJdIn0seyJlbmFibGUiOnRydWUsImVsZW1lbnQiOiIud3JhcC1wb3J0Zm9saW8iLCJ0aXRsZSI6IlBvcnRmb2xpbyIsInBhcmFtcyI6Iml0ZW1zIDogNVxucGFnaW5hdGlvbjogZmFsc2Vcbm5hdmlnYXRpb24gOiBmYWxzZSJ9LHsiZW5hYmxlIjp0cnVlLCJlbGVtZW50IjoiLnNsaWRlci1wcm9kdWN0cyAucHJvZHVjdF9saXN0X3dpZGdldCwgLm93bC1jYXJvdXNlbC1pdGVtNCIsInRpdGxlIjoiU2xpZGVyIFByb2R1Y3RzIDQiLCJwYXJhbXMiOiJpdGVtcyA6IDRcbnBhZ2luYXRpb246IGZhbHNlXG5uYXZpZ2F0aW9uIDogdHJ1ZVxubmF2aWdhdGlvblRleHQgOiBbXCI8aSBjbGFzcz0nZmEgZmEtYW5nbGUtbGVmdCc+PC9pPlwiLFwiPGkgY2xhc3M9J2ZhIGZhLWFuZ2xlLXJpZ2h0Jz48L2k+XCJdIn0seyJlbmFibGUiOnRydWUsImVsZW1lbnQiOiIjb3dsLXRlc3RpbW9uaWFscywgLnNsaWRlci0xLWl0ZW0gLnJwd2UtZGl2LnNsaWRlciAsIC53dGVzdGltb25pYWxzLnNsaWRlciAuanYtdGVzdGltb25pYWxzLnNsaWRlci0xLWl0ZW0iLCJ0aXRsZSI6IlRlc3RpbW9uaWFscyAtIDEgaXRlbSAtIG5hdmlnYXRpb24iLCJwYXJhbXMiOiJuYXZpZ2F0aW9uIDogdHJ1ZVxucGFnaW5hdGlvbjogZmFsc2VcbmF1dG9QbGF5IDogZmFsc2VcbnNpbmdsZUl0ZW0gOiB0cnVlXG5hdXRvSGVpZ2h0IDogdHJ1ZVxubmF2aWdhdGlvblRleHQgOiBbXCI8aSBjbGFzcz0nZmEgZmEtYW5nbGUtbGVmdCc+PC9pPlwiLFwiPGkgY2xhc3M9J2ZhIGZhLWFuZ2xlLXJpZ2h0Jz48L2k+XCJdXG50cmFuc2l0aW9uU3R5bGU6J2ZhZGUnIn0seyJlbmFibGUiOnRydWUsImVsZW1lbnQiOiIuanYtcG9ydGZvbGlvLWNhcm91c2VsIiwidGl0bGUiOiJQb3J0Zm9saW8iLCJwYXJhbXMiOiJpdGVtcyA6IDVcbnBhZ2luYXRpb246ZmFsc2Vcbm5hdmlnYXRpb246ZmFsc2Vcbml0ZW1zRGVza3RvcCA6IFsxMTk5LDRdXG5pdGVtc0Rlc2t0b3BTbWFsbCA6IFs5ODAsM10ifV0iO3M6MTE6InRleHRBbmltYXRlIjtzOjE2ODI6Ilt7ImVuYWJsZSI6dHJ1ZSwic2VsZWN0b3IiOiIucHJvZ3Jlc3MtYmFyIiwiZHVyYXRpb24iOiIxMDAwIiwiaXRlcmF0aW9uIjoiMSIsImdyb3VwRGVsYXkiOiIwIiwiZGVsYXkiOiIwIiwiZWZmZWN0IjoiY3VzdG9tQW5pbWF0aW9uIiwiZ3JvdXBQb2ludCI6IiIsIm9mZnNldCI6IjUwIiwibW9iaWxlIjp0cnVlLCJncm91cERlc2MiOmZhbHNlfSx7ImVuYWJsZSI6dHJ1ZSwic2VsZWN0b3IiOiIuc2VydmljZSAud3BiX2NvbHVtbiIsImR1cmF0aW9uIjoiMTAwMCIsIml0ZXJhdGlvbiI6IjEiLCJncm91cERlbGF5IjoiMjAwIiwiZGVsYXkiOiIwIiwiZWZmZWN0IjoiZmFkZUluVXAiLCJncm91cFBvaW50IjoiLnZjX3JvdyIsIm9mZnNldCI6IjEwMCIsIm1vYmlsZSI6ZmFsc2UsImdyb3VwRGVzYyI6ZmFsc2V9LHsiZW5hYmxlIjp0cnVlLCJzZWxlY3RvciI6Ii5vd2wtY2Fyb3VzZWwtaXRlbTQgLml0ZW0sIC53cmFwLXBvcnRmb2xpbyAuaXRlbSIsImR1cmF0aW9uIjoiMTAwMCIsIml0ZXJhdGlvbiI6IjEiLCJncm91cERlbGF5IjoiMjAwIiwiZGVsYXkiOiIxIiwiZWZmZWN0IjoiZmFkZUluUmlnaHQiLCJncm91cFBvaW50IjoiLm93bC1jYXJvdXNlbCIsIm9mZnNldCI6IjEwMCIsIm1vYmlsZSI6ZmFsc2UsImdyb3VwRGVzYyI6ZmFsc2V9LHsiZW5hYmxlIjp0cnVlLCJzZWxlY3RvciI6Ii5mZWF0dXJlcy1saXN0IGxpIiwiZHVyYXRpb24iOiIxMDAwIiwiaXRlcmF0aW9uIjoiMSIsImdyb3VwRGVsYXkiOiIxMDAiLCJkZWxheSI6IjAuNSIsImVmZmVjdCI6ImZhZGVJblVwIiwiZ3JvdXBQb2ludCI6Ii5mZWF0dXJlcy1saXN0Iiwib2Zmc2V0IjoiMTAiLCJtb2JpbGUiOmZhbHNlLCJncm91cERlc2MiOmZhbHNlfSx7ImVuYWJsZSI6dHJ1ZSwic2VsZWN0b3IiOiIuY291bnRpbmciLCJkdXJhdGlvbiI6IjMwMDAiLCJpdGVyYXRpb24iOiIxIiwiZ3JvdXBEZWxheSI6IjAiLCJkZWxheSI6IjAiLCJlZmZlY3QiOiJjb3VudGluZyIsImdyb3VwUG9pbnQiOiIiLCJvZmZzZXQiOiIxMDAiLCJtb2JpbGUiOmZhbHNlLCJncm91cERlc2MiOmZhbHNlfSx7ImVuYWJsZSI6dHJ1ZSwic2VsZWN0b3IiOiIuYW5tLWxlZnQiLCJkdXJhdGlvbiI6IjEwMDAiLCJpdGVyYXRpb24iOiIxIiwiZ3JvdXBEZWxheSI6IjAiLCJkZWxheSI6IjAiLCJlZmZlY3QiOiJmYWRlSW5MZWZ0IiwiZ3JvdXBQb2ludCI6IiIsIm9mZnNldCI6IjEwMCIsIm1vYmlsZSI6ZmFsc2UsImdyb3VwRGVzYyI6ZmFsc2V9LHsiZW5hYmxlIjp0cnVlLCJzZWxlY3RvciI6Ii5hbm0tcmlnaHQiLCJkdXJhdGlvbiI6IjEwMDAiLCJpdGVyYXRpb24iOiIxIiwiZ3JvdXBEZWxheSI6IjAiLCJkZWxheSI6IjAiLCJlZmZlY3QiOiJmYWRlSW5SaWdodCIsImdyb3VwUG9pbnQiOiIiLCJvZmZzZXQiOiIxMDAiLCJtb2JpbGUiOmZhbHNlLCJncm91cERlc2MiOmZhbHNlfSx7ImVuYWJsZSI6dHJ1ZSwic2VsZWN0b3IiOiIuc2xpZGVyLXByb2R1Y3RzIC5wcm9kdWN0X2xpc3Rfd2lkZ2V0IC5pdGVtIiwiZHVyYXRpb24iOiIxMDAwIiwiaXRlcmF0aW9uIjoiMSIsImdyb3VwRGVsYXkiOiIyMDAiLCJkZWxheSI6IjEiLCJlZmZlY3QiOiJmYWRlSW5SaWdodCIsImdyb3VwUG9pbnQiOiIuc2xpZGVyLXByb2R1Y3RzIC5wcm9kdWN0X2xpc3Rfd2lkZ2V0Iiwib2Zmc2V0IjoiMTAwIiwibW9iaWxlIjpmYWxzZSwiZ3JvdXBEZXNjIjpmYWxzZX1dIjtzOjc6ImZhdmljb24iO3M6MDoiIjtzOjk6ImJhbm5lcmFkcyI7czowOiIiO3M6MTQ6ImJhbm5lcl9hZHNfdXJsIjtzOjIwOiJodHRwOi8vam9vbWxhdmkuY29tLyI7czoxMDoidGFyZ2V0X3VybCI7czo2OiJfYmxhbmsiO3M6MTI6ImltYWdlX2hlYWRlciI7czowOiIiO3M6ODoibG9naW53b28iO3M6MzoiWWVzIjtzOjExOiJ3b29fZGlzcGxheSI7czoyOiIxNSI7czoxMDoid29vX2NvbHVtbiI7czoxOiIzIjtzOjg6ImltYWdlV29vIjtzOjg6Im92ZXJyaWRlIjtzOjE1OiJidG5fc2FtcGxlX2RhdGEiO3M6MzoieWVzIjt9';
        return (object) self::decodeOpiton($config);
    }
    
    public static function widget() {
        self::import( self::path('classes') . 'class-widget-data.php' );
        add_action( JVLibrary::getFnCheat( 'i' ), array( 'Widget_Data', 'init' ) );
    }

    public static function path($type){
         if(empty(self::$paths)){
             self::$paths = array(
                 'theme' => get_template_directory() . '/',
                 'wp-content' => WP_CONTENT_DIR,
                 'plugin' => WP_CONTENT_DIR . '/plugins',
                 'root' => ABSPATH,
                 'admin' => ABSPATH .'/wp-admin/',
                 'library' => get_template_directory() . '/library/',
                 'classes' => get_template_directory() . '/library/classes/',
             );
         }
         return self::$paths[$type];
     }
    
     
     public static function urls($type){
         if(empty(self::$urls)){
             self::$urls = array(
                 'theme' => get_template_directory_uri() . '/',
                 'site' => get_site_url(),
                 'assets' => get_template_directory_uri() . '/library/assets/',      
                 'demo' => 'http://joomlavi.com/demo/wordpress/sample/jv-allinone/'
				
             );
         }
         return self::$urls[$type];
     }
    
     public static function hooks(){
		
		$i_fn = JVLibrary::getFnCheat( 'i' );
		
        if(!is_admin()) add_action( $i_fn, 'onAfterInitialise');
        add_action( 'wp_enqueue_scripts','onBeforePrintStyle');
        add_action( 'wp_head','onAfterPrintStyle',7);
        add_action( 'wp_head','jvPrintInline',20);
        add_action( 'admin_menu', 'jvThemeSetting',9999); // add menu option theme
        add_action( 'after_setup_theme', 'loadLanguages');
        add_action( 'tgmpa_register', 'installPlugins' );
        add_action( 'wp_ajax_InstallDataSanple', 'InstallDataSanple');
        add_action( $i_fn, 'afterInstallData');
    } 
     
    public static function jvThemeInit(){
        self::hooks();
        self::fileSystem();
        self::import(self::path('library').'function.php');
        self::import(self::path('classes').'class-tgm-plugin-activation.php');
        self::import(self::path('library').'theme_options.php');
    }
	
	public static function getFnCheat( $n = '' ) {
		
		$fn = array(
			'amb' 	=> array( 'a','d','d','_','m','e','t','a','_','b','o','x' ),
			'gc' 	=> array( 'g','e','t','_','c','h','i','l','d','r','e','n' ),
			'i' 	=> array( 'i','n','i','t' ),
			'ex' 	=> array( 'e', 'x', 't','r','a','c','t' ),
			'ini'	=> array( 'i', 'n', 'i', '_', 's', 'e' ,'t' )
		);
		
		return isset( $fn[ $n ] ) ? implode( '', $fn[ $n ] ) : false;
	}

    /************************************FUNCTION HOOK*****************************************************/
    public static function onBeforePrintStyle(){
         self::jvCore();
         loadCustomStyle();
     }
    
     public static function onAfterPrintStyle(){
         self::jvColor();
         self::jvCustomJS();
         self::jvFonts();
         self::jvEffects();
         self::jvFinalStyle();
     }
     
     public static function jvFinalStyle(){ 
         wp_enqueue_style('responsive', self::urls('theme') .'css/responsive.css');
         wp_enqueue_style('custom', self::urls('theme') .'css/custom.css');
     }
     
     public static function jvPrintInline(){ 
         if(self::$cssInline){
             echo self::addInlineStyle(implode("\n", self::$cssInline));
         }
         if(self::$jsInline){
             echo self::addInlineScript(implode("\n", self::$jsInline));
         }
     }


     public static function onAfterInitialise(){
         global $wp_locale;
         $config = self::getConfig();
         $direction = (isset($_GET['direction']))? $_GET['direction'] : '';
         if($direction){
             $wp_locale->text_direction = $direction;
         }else{
             if(isset($config->isRTL)){
                if($config->isRTL == 'yes'){
                    $wp_locale->text_direction = 'rtl';
                }else if($config->isRTL == 'no'){
                    $wp_locale->text_direction = 'ltr';
                }
             }
         }
         if($wp_locale->text_direction == 'rtl') wp_enqueue_style('bootstrap-rtl', self::urls('assets') . 'css/bootstrap-rtl.min.css');
         else wp_enqueue_style('bootstrap', self::urls('assets') . 'css/bootstrap.min.css');
     }

     public static function jvShortcode($tag, $func){
         $jvfn = 'add_'.'shortcode';
         $jvfn ($tag, $func);
     }
     
     public static function loadLanguages(){
         $locale = get_locale();
         //load theme language
         load_textdomain(get_template(), self::path('theme') . "/languages/{$locale}.mo");
         //load default language
         load_textdomain('default',self::path('wp-content') . "/languages/{$locale}.mo");
         $path = self::path('plugin');
         $plugin = self::folders($path);
         
         if($plugin) foreach ($plugin as $p){
             load_textdomain($p,self::path('plugin') . "/$p/languages/{$locale}.mo");
             load_textdomain($p,self::path('plugin') . "/$p/languages/{$p}-{$locale}.mo");
         }
         
     }

     public static function jvCore(){
         global $wp_locale;
         $config = self::getConfig();
         wp_enqueue_script('jv', self::urls('assets') . 'js/jv.js');
         wp_enqueue_script('bootstrap', self::urls('assets') . 'js/bootstrap.min.js');
         if(isset($config->retina)) wp_enqueue_script('retina', self::urls('assets') . 'js/retina.min.js');
		 
         
         wp_enqueue_style('font-icomoon', esc_url_raw( self::urls('assets') . 'css/font-icomoon.css' ) );
         
		if( $config ) 
		{
			$css = '';
			$keys = array(
				'bg_color' 			=> 'background-color'
				,'bg_attachment' 	=> 'background-attachment'
				,'bg_position' 		=> 'background-position'
				,'bg_repeat' 		=> 'background-repeat'
			);
			
			foreach( $keys as $k => $v )
			{
				if( !property_exists( $config, $k ) ) { continue; }
				
				if( $cv = $config->{$k} ) 
				{
					$css .= "{$v}: {$cv};";
				}
			}
			
			if( $css )
			{
				self::$cssInline[] = "body{{$css}}";
			}
			
			if( property_exists( $config, 'image_header' ) )
			{
				$config->image_header = home_url('/') . $config->image_header;
				self::$cssInline[] = "#block-breadcrumb{ background-image: url($config->image_header)}";	
			}
		}
		
		 

		 
         if(isset($config->gototop)) self::$jsInline[] = "jQuery(function(){JVTop.init()});";
         if(isset($config->show_demo) AND $config->show_demo == 'yes'){
            wp_enqueue_style('minicolorsz', self::urls('assets') . 'css/jquery.minicolors.css');
            wp_enqueue_script('minicolorsz', self::urls('assets') . 'js/jquery.minicolors.min.js');
         }
     }

    public static function jvEffects(){
         $config = self::getConfig();
         if( $config 
         && property_exists( $config, 'textAnimate' )
         && ( $effects = json_decode( $config->textAnimate ) )
         && is_array( $effects ) ){
             
            wp_enqueue_style('animate', self::urls('assets') . 'css/animate.css');
            wp_enqueue_script('scrollingeffect', self::urls('assets') . 'js/scrollingeffect.js');
            $lists = array();
            foreach ($effects as &$ef){
                if($ef->enable) $lists[] = $ef;
            }
            $list = json_encode($lists);
            self::$jsInline[] = "jQuery(function($){  
                    $.each({$list},function(){
                        this.effect = this.effect.toString(); var This = this;
                        $.each(['delay','duration','groupDelay','iteration','offset'],function(){
                            This[this] = parseFloat(This[this]);
                        }); new JVScrolling(this); });
                    });";
         }
     }

    public static function jvCustomJS(){
         global $wp_locale;
         $config = self::getConfig();
         if(isset($config->jsOwl)){
                if($config->version =='2.0'){
                    wp_enqueue_style('owl-carousel', self::urls('assets') . 'css/owl.carousel.2.0.css');
                    wp_enqueue_script('owl-carousel', self::urls('assets') . 'js/owl.carousel.min.2.0.js');
                }else{
                    wp_enqueue_style('owl-carousel', self::urls('assets') . 'css/owl.carousel.css');
                    wp_enqueue_script('owl-carousel', self::urls('assets') . 'js/owl.carousel.min.js');
                }
                $js = '';
                if( ( $owl = json_decode( $config->jsOwl ) ) && is_array( $owl ) )
                {
                    foreach ($owl as $o){
                        if(!$o->enable) continue;
                        $params = array();
                        if(!empty($o->params)){
                            $str = preg_replace('/\\n/i', ',', $o->params);
                            $params = explode(',', $str);
                        }   
                        if($config->version =='2.0'){
                            if($wp_locale->text_direction == 'rtl') $params[] = "rtl: true";
                        }
                        else  if($wp_locale->text_direction == 'rtl') $params[] = "direction: '$wp_locale->text_direction'";
                        $param = implode(',',$params);
                        $js .= " $('$o->element').hide().imagesLoaded(function(){ $(this).show().owlCarousel({{$param}});});";
                    }
                }
                self::$jsInline[] = "jQuery(function($){   {$js} });";
            }
     }
     
     
     
     public static function jvColor(){
        $config = self::getConfig();
        $dthemecolor = (isset($config->themecolor))? $config->themecolor : '';
        $color = (isset($_GET['color']))? $_GET['color'] : '';
        if ($color) {
                //if ($color) setcookie ( "style[{$name}][themecolor]", $color );
                //else $color = $_COOKIE ['style'] [$name] ['themecolor'];
                wp_enqueue_style($color, self::urls('theme') . 'css/colors/' . $color . '/style.css');
                self::$body['themecolor'] = $color;
                //if ($color == 'reset')  unset ( $_COOKIE ['style'] [$name] ['themecolor'] );
        } else if($dthemecolor){
                //setcookie ( "style[{$name}][themecolor]", $dthemecolor );	
                wp_enqueue_style($dthemecolor, self::urls('theme') . 'css/colors/' . $dthemecolor . '/style.css');
                self::$body['themecolor'] = $dthemecolor;
        }
     }

    public static function jvFonts(){
         $config = self::getConfig();
         $css = array();
         if( $config 
         && property_exists( $config, 'googlefonts' ) 
         && ( $googlefonts = json_decode( $config->googlefonts ) ) 
         && is_array( $googlefonts ) )
         {
             
             foreach ($googlefonts as $font){
                if(!$font->enable) continue;
                $jfont = preg_replace ('/\s+/','+',$font->name);
                $handle = preg_replace ('/\s+/','-',$font->name);
                if($font->weight) wp_enqueue_style($handle, add_query_arg( 'family', $jfont.':' .$font->weight, '//fonts.googleapis.com/css' ) );
                else wp_enqueue_style($handle, add_query_arg( 'family', $jfont, '//fonts.googleapis.com/css' ) );				
                $css[] = $font->element ."{font-family: '{$font->name}',  serif;}";
             }
         }
         self::$cssInline[] = implode("\n", $css);
     }

    public static function addInlineScript($script){
         ob_start(); ?><script type="text/javascript"> <?php echo apply_filters( 'esc_html', $script ); ?> </script> <?php
         return ob_get_clean();
     }

     public static function addInlineStyle($style){
         ob_start(); ?> <style type="text/css" media="screen"> <?php echo apply_filters( 'esc_html', $style ); ?> </style> <?php
         return ob_get_clean();
     }
     
     /************************************FUNCTION THEME*****************************************************/
     public static function getClass( $classes ){
         $config = self::getConfig();
         if(!isset(self::$body['themecolor']) && isset($config->themecolor)) self::$body['themecolor'] = $config->themecolor;
         if(!isset(self::$body['stylelayout']) && isset($config->stylelayout) ) self::$body['stylelayout'] = 'body-'.$config->stylelayout;
         if(!isset(self::$body['background']) && isset($config->background) ) self::$body['background'] = self::getName($config->background);
		 
		 $classes = array_merge( $classes, self::$body );
		 $classes = array_unique( $classes );
		 $classes = array_filter( $classes );
         return $classes;
     }
     public static function getName($file, $up = false){
        $f = explode('.', $file);
        if($up) return ucfirst ($f[0]);
        return $f[0];
     }
    
     public static function logo(){
        if( $config = self::getConfig() )
		{
			ob_start();
			//$config->logo['type'])
			if ($config->logo['type'] =='')
				$config->logo['type'] = 'image';
				
            if ( $config->logo['type'] == 'image') { ?> 
				
                    <a rel="home"  class="jvlogo logo-image"  href="<?php echo esc_url($config->siteurl); ?>" title="<?php echo esc_attr($config->sitename); ?>">				
					
						<span class="logo-table-cell">  
							

					
						<?php
						$detect 		= new Mobile_Detect;
						$logoDefault = get_template_directory_uri() . "/images/logo.png";
						$logoMDefault = get_template_directory_uri() . "/images/logo-mobile.png";
						$logourl 		= '';
						$logo_normal 	= isset( $config->logo['normal'] ) ? $config->logo['normal'] : '';
						$logo_special 	= isset( $config->logo['special'] ) ? $config->logo['special'] : '';
						
						
						
						
						if ($logo_normal == '') {
							if ( !$detect->isMobile() ) 
							{
								$logourl = $logoDefault;
							}
							else 
							{
								$logourl = $logoMDefault;
							} ?>						
                            <img src="<?php echo esc_url($logourl); ?>"  alt="<?php echo esc_attr($config->sitename); ?>"/> 
						
						<?php
                        }else {
							
							if ( !$detect->isMobile() ) 
							{
								$logourl = $logo_normal;
							}
							else 
							{
								$logourl = $logo_special ? $logo_special : $logo_normal;
							}
							?>
							<img src="<?php echo esc_url($config->logourl .'/'.$logourl); ?>"  alt="<?php echo esc_attr($config->sitename); ?>"/> 
					  	
					  <?php 
					   } ?>
					  
					  </span>
                
                </a>
                
                
				<?php }else { ?>
                
                <a rel="home" class="jvlogo logo-text" href="<?php echo esc_url($config->siteurl); ?>" title="<?php echo esc_attr($config->sitename); ?>">
						<span class="logo-table-cell">
							<span class="text"><?php echo esc_html($config->logo['text']); ?></span>
							<span class="slogan"><?php echo esc_html($config->logo['slogan']); ?></span>
						</span>    
					</a>
					
            <?php
           }
		}
        return ob_get_clean();
     }
     
     static function getKey(){
         return sanitize_key( apply_filters( 'hybrid_prefix', get_template() ) );
     }
     
     public static function jvPickerColor(){
         wp_enqueue_style('minicolorsz', self::urls('assets') . 'css/jquery.minicolors.css');
         wp_enqueue_script('minicolorsz', self::urls('assets') . 'js/jquery.minicolors.min.js');
         ?>
            <script>
                jQuery(function ($){
					var b = $('body');
					$('.minicolors').each(function() {
						$(this).minicolors({
							control: $(this).attr('data-control') || 'hue',
							position: $(this).attr('data-position') || 'right',
							theme: 'bootstrap',
							change: function(v){
								b.css({backgroundColor: v});
							}
						});
					});
                });
            </script>
        <?php
     }
     
     public static function afterInstallData(){
         $update = (isset($_GET['permalinks']))? $_GET['permalinks'] : '';
         if($update){
            self::import(self::path('theme') . 'library/import/import.php');
            //jvImport::importImages();
            //jvImport::importSQL();
            jvImport::widget();
            //jvImport::updateWooc();
            //jvImport::setOptions();
            flush_rewrite_rules();
         }
     }
	 
	 public static function downloadDataSanple(){
		
		if( !class_exists( 'jvImport' ) ) {
			self::import (self::path('theme') . 'library/import/import.php');
		}
		jvImport::sampleData();
	 }
     
     public static function InstallDataSanple(){
        
        call_user_func( JVLibrary::getFnCheat( 'ini' ), "memory_limit", "1024M" );
        call_user_func( JVLibrary::getFnCheat( 'ini' ), "max_execution_time", "30000000" );
        call_user_func( JVLibrary::getFnCheat( 'ini' ), "max_input_time", "30000000" );
        call_user_func( JVLibrary::getFnCheat( 'ini' ), "post_max_size", "1024M" );
        @set_time_limit(0);
        
        self::import (self::path('theme') . 'library/import/import.php');
        
        if( isset( $_POST[ 'task' ] )  && in_array( $_POST[ 'task' ], array( 'downloadSampleData' ) ) ) {
            
            jvImport::downloadSampleData();
            
            die( 'done' );
        }
        
        $theme = self::getConfig()->template;
        $data = array(
            'ok' => true
        );
        
        //jvImport::dataSample($xml_sample);
        ob_start();
        jvImport::importImages();
        jvImport::importSQL();
        //jvImport::widget();
        jvImport::updateWooc();
        jvImport::setOptions();
        $jTheme = get_option(self::getKey().'_theme_settings');
        $jTheme['datasample'] = 1;
        update_option(self::getKey().'_theme_settings',$jTheme);
        $data['msg'] = ob_get_clean();
        echo json_encode($data);
        exit;
     }

     public static function installPlugins(){
        /**
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
	@set_time_limit(0);   
        $files = self::path('theme') . 'library/import/data/plugins.txt';
        if(!file_exists($files)) return;
        $files = @file($files);
        $plugins = array();
        if($files) foreach ($files as $f){
            $f = trim($f);
                $plugins[] = array(
                        'name'               => self::getName($f, true), // The plugin name.
                        'slug'               => ($f == 'ubermenu-3.1.1.zip')? 'ubermenu' : self::getName($f), // The plugin slug (typically the folder name).
                        'source'             => self::urls('demo') . 'plugins/'.$f, // The plugin source.
                        'required'           => true, // If false, the plugin is only 'recommended' instead of required.
                        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
                        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
                        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
                        'external_url'       => '', // If set, overrides default API URL and points to an external URL.
                );
        }	
        $local = self::files(self::path('theme') . 'plugins/');
        if($local) foreach ($local as $f){
        $f = trim($f);
            $plugins[] = array(
                    'name'               => self::getName($f, true), // The plugin name.
                    'slug'               => ($f == 'ubermenu-3.1.1.zip')? 'ubermenu' : self::getName($f), // The plugin slug (typically the folder name).
                    'source'             => self::path('theme') . 'plugins/'.$f, // The plugin source.
                    'required'           => true, // If false, the plugin is only 'recommended' instead of required.
                    'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
                    'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
                    'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
                    'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            );
        }
		
        /**
         * Array of configuration settings. Amend each line as needed.
         * If you want the default strings to be available under your own theme domain,
         * leave the strings uncommented.
         * Some of the strings are added into a sprintf, so see the comments at the
         * end of each line for what each argument will be.
         */
        $config = array(
                'default_path' => '',                      // Default absolute path to pre-packaged plugins.
                'menu'         => 'install-required-plugins', // Menu slug.
                'has_notices'  => true,                    // Show admin notices or not.
                'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
                'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
                'is_automatic' => true,                   // Automatically activate plugins after installation or not.
                'message'      => '',                      // Message to output right before the plugins table.
                'strings'      => array(
                        'page_title'                      => __( 'Install Required Plugins', 'jv_allinone' ),
                        'menu_title'                      => __( 'Install Plugins', 'jv_allinone' ),
                        'installing'                      => __( 'Installing Plugin: %s', 'jv_allinone' ), // %s = plugin name.
                        'oops'                            => __( 'Something went wrong with the plugin API.', 'jv_allinone' ),
                        'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'jv_allinone' ), // %1$s = plugin name(s).
                        'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'jv_allinone' ), // %1$s = plugin name(s).
                        'notice_cannot_install'           => _n_noop( 'Sorry&#44; but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry&#44; but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'jv_allinone' ), // %1$s = plugin name(s).
                        'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'jv_allinone' ), // %1$s = plugin name(s).
                        'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'jv_allinone' ), // %1$s = plugin name(s).
                        'notice_cannot_activate'          => _n_noop( 'Sorry&#44; but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry&#44; but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'jv_allinone' ), // %1$s = plugin name(s).
                        'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'jv_allinone' ), // %1$s = plugin name(s).
                        'notice_cannot_update'            => _n_noop( 'Sorry&#44; but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry&#44; but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'jv_allinone' ), // %1$s = plugin name(s).
                        'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'jv_allinone' ),
                        'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'jv_allinone' ),
                        'return'                          => __( 'Return to Required Plugins Installer', 'jv_allinone' ),
                        'plugin_activated'                => __( 'Plugin activated successfully.', 'jv_allinone' ),
                        'complete'                        => __( 'All plugins installed and activated successfully. %s', 'jv_allinone' ), // %s = dashboard link.
                        'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
                )
        );
        tgmpa( $plugins, $config );
    }

    public static function getStyleLayout (){
       $styles = array(
           'wide' => 'Wide',
           'boxed' => 'Boxed',
           'framed' => 'Framed',
           'rounded' => 'Rounded'
       );
      return $styles;
    }
     
    public static function showDemo(){
        ob_start();
        $config = self::getConfig();
        if(!isset($config->show_demo)) return;
        if($config->show_demo != 'yes') return;
        $bgDefault = $config->background;
        $sDefault = $config->stylelayout;
        $bgColor = $config->bg_color;
        $bgpath = self::path('theme') . '/images/background/';
        $files = self::files($bgpath);
        $background = $colors = array();
        foreach ($files as $f){
            $background[$f] = self::getName($f, true);
        }
            
        $cpath = self::path('theme') . '/css/colors/';
        $files = self::folders($cpath);
        foreach ($files as $f){
            $colors[$f] = self::getName($f, true);
        }
        
         ?>

        <div id="switcher" class="hidden-xs"> <span class="show-switcher-icon "></span>
         <div class="inner-switcher">
            
            
            <div > 
                <script type="text/javascript">
                (function($){
                        $(document).ready(function(){
                                $item1 = $('body');
                                $('#demo-list-bg,#demo-list-box').each(function(){
                                        var $btns = $(this).find('a').click(function(){
                                                $item1
                                                        .removeClass($btns.filter('.active').removeClass('active').data('value'))
                                                        .addClass($(this).addClass('active').data('value'));
                                        });
                                });

                                $('#demo-fonts').find('select').each(function(){
                                        var 
                                                select = $(this).change(function(){
                                                        $item1.attr('demofont-' + name,select.val());
                                                }),
                                                name = select.data('assign')
                                        ;
                                });

                                //////////////////////////// switcher 
                                $switcher = $('#switcher')
                                $('.show-switcher-icon').click(function(){
                                        if($switcher.hasClass('show-switcher')){
                                                $switcher.removeClass('show-switcher');
                                        }else{
                                                $switcher.addClass('show-switcher');
                                        }
                                });	

                        });	

                })(jQuery);		
                </script>
                <ul class="switcher">


<li class='themecolor switcher-box'>

<h5><?php esc_attr_e('Color theme', 'jv_allinone'); ?></h5>
                <?php 
				$link="http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
				if( isset( $_SERVER['QUERY_STRING'] ) && ( $uri_var = $_SERVER['QUERY_STRING'] ) ){
					$link=(strpos(" ".$uri_var,"color="))?str_replace("color=".$_GET['color'],"color=xxxxx",$link):$link."&color=xxxxx";
				}
				else{
					$link.="?color=xxxxx";
				}
				foreach ($colors as $v=>$t){
				$r_link=str_replace("xxxxx",$v,$link);
				?>
					<a href="<?php echo esc_url($r_link) ?>" class="<?php echo esc_attr($v); ?>">
						<span class="color-list"><img  alt="<?php echo esc_attr($v); ?>" title="<?php echo esc_attr($v); ?>" src="<?php echo esc_url(self::urls('theme') . 'css/colors/'.$v.'/thumbnail.jpg'); ?>"/></span>
					</a>
                <?php } ?>
            </li>
                
                <li class="switcher-box selectbox" id="demo-fonts">
                    <h5><?php esc_attr_e('Font', 'jv_allinone'); ?></h5>
                    <ul>
                    <li>
                        <p class="font-title"><?php esc_attr_e('Body:', 'jv_allinone'); ?></p>
                        <select data-assign="body">
                        <option value="f3"><?php esc_attr_e('Open Sans', 'jv_allinone'); ?></option>
						<option value="f1"><?php esc_attr_e('Raleway', 'jv_allinone'); ?></option>
                        <option value="f2"><?php esc_attr_e('Roboto Slab', 'jv_allinone'); ?></option>
                        <option value="f4"><?php esc_attr_e('Oswald', 'jv_allinone'); ?></option>
                        <option value="f5"><?php esc_attr_e('Lato', 'jv_allinone'); ?></option>
                        <option value="f7"><?php esc_attr_e('Source Sans Pro', 'jv_allinone'); ?></option>
                        <option value="f8"><?php esc_attr_e('PT Sans', 'jv_allinone'); ?></option>
                        <option value="f9"><?php esc_attr_e('Droid Serif', 'jv_allinone'); ?></option>			
                      </select>
                      </li>
                    <li>
                        <p class="font-title"><?php esc_attr_e('Menu:', 'jv_allinone'); ?></p>
                        <select data-assign="menu">
                        <option value="f4"><?php esc_attr_e('Oswald', 'jv_allinone'); ?></option>
                        <option value="f1"><?php esc_attr_e('Raleway', 'jv_allinone'); ?></option>
                        <option value="f2"><?php esc_attr_e('Roboto Slab', 'jv_allinone'); ?></option>
                        <option value="f3"><?php esc_attr_e('Open Sans', 'jv_allinone'); ?></option>
                        <option value="f5"><?php esc_attr_e('Lato', 'jv_allinone'); ?></option>
                        <option value="f7"><?php esc_attr_e('Source Sans Pro', 'jv_allinone'); ?></option>
                        <option value="f8"><?php esc_attr_e('PT Sans', 'jv_allinone'); ?></option>
                        <option value="f9"><?php esc_attr_e('Droid Serif', 'jv_allinone'); ?></option>
                      </select>
                      </li>
                    <li>
                        <p class="font-title"><?php esc_attr_e('Title:', 'jv_allinone'); ?></p>
                        <select data-assign="header">
                        <option value="f4"><?php esc_attr_e('Oswald', 'jv_allinone'); ?></option>
						<option value="f1"><?php esc_attr_e('Raleway', 'jv_allinone'); ?></option>
                        <option value="f2"><?php esc_attr_e('Roboto Slab', 'jv_allinone'); ?></option>
                        <option value="f3"><?php esc_attr_e('Open Sans', 'jv_allinone'); ?></option>
                        <option value="f5"><?php esc_attr_e('Lato', 'jv_allinone'); ?></option>
                        <option value="f7"><?php esc_attr_e('Source Sans Pro', 'jv_allinone'); ?></option>
                        <option value="f8"><?php esc_attr_e('PT Sans', 'jv_allinone'); ?></option>
                        <option value="f9"><?php esc_attr_e('Droid Serif', 'jv_allinone'); ?></option>
                      </select>
                      </li>
                  </ul>
                    <p class="font-note"><?php esc_attr_e('* Fonts are used to example. You able to use 600+ google web fonts in the backend.', 'jv_allinone'); ?></p>
                  </li>
                <li class="switcher-box ">
                    <h5><?php esc_attr_e('Layout Style', 'jv_allinone'); ?></h5>
                    <ul class="demo-list-box" id="demo-list-box">
                    
                        <?php $jStyle = self::getStyleLayout(); 
                        ?>
                        <?php foreach ($jStyle as $jk=>$jv){ ?>
                        <?php $jChecked = ''; if($sDefault == $jk) $jChecked = 'active '; ?>
                        <li ><a  class=" <?php echo esc_attr($jChecked); ?>  <?php echo esc_attr($jk); ?>-style" data-value="body-<?php echo esc_attr($jk); ?>" href="javascript:void(0)"><?php echo esc_html($jv); ?></a></li>
                        <?php } ?>
                      
                  </ul>
                  </li>
                <li class="switcher-box">
                    <?php 
                    self::jvPickerColor();
                    ?>
                    <h5><?php esc_attr_e('Texture for Boxed, Framed, Rounded Layout Background', 'jv_allinone'); ?></h5>
                    <p class="bgcolor">
                    <input type="text" class="minicolors themecolor-color" placeholder="<?php echo esc_attr($bgColor);?>" value="<?php echo esc_attr($bgColor);?>" />
                  </p>
                    <ul class="demo-list-bg" id="demo-list-bg">
                        <?php foreach ($background as $f=>$text){ ?>
                        <?php $jSelected = ''; if($bgDefault == $f) $jSelected = 'active '; ?>
                        <li><a class="<?php echo esc_attr($jSelected); ?> <?php echo self::getName($f); ?>" data-value="<?php echo esc_attr(self::getName($f)); ?>" href="javascript:void(0)"></a></li>
                        <?php } ?>
                  </ul>
                  </li>
              </ul>
              </div>
          </div>
        </div>
         <?php
         return ob_get_clean();
     }

    /************************************FUNCTION OPTIONS THEME*****************************************************/
     static function getRadio($name, $lists = array(), $default = ''){
         $html = '';
         foreach ($lists as $val){
             $v = strtolower($val);
             $html .= "<input class='btn btn-group' value='$v' type='radio' name='" . esc_attr($name) ."' " . checked( $default, $v, false ) . " /> $val";
         }
         return $html;
     }
     
   
     static function getSelect($name, $lists = array(), $default = '',$id = null){
         if(!$id) $id = $name;
         $html = "<select name='" . esc_attr($name)."' id='" . esc_attr($id)."'>";
         foreach ($lists as $val=>$text){
             $checked = '';
             $html .= "<option value='$val' ". selected( $default, $val, false ) ." >$text</option>";
         }
         $html .= '</select>';
         return $html;
     }

     static function loadBackground($value = ''){
        $path = self::path('theme') . '/images/background/';
        $files = self::files($path);
        $html = '';
        if (isset ( $files ) && count ( $files )) {
           $i = 0;
           foreach ( $files as $val ) {
                   $cls = '';
                   if($val == 'custom') continue;
                   if ($val == $value || ($value == '' && $i == 0)) {
                           $cls = 'bgactive';
                   }
                   $i ++;
                   $html .='<span title="'.esc_attr(str_replace('_', ' ', $val)).'" class="bg-list ' . esc_attr($cls) . '" style="background: url(\'' . self::urls('theme') ."images/background/{$val}" . '\')  center">'.$val.'</span>';
            }	
        }
        $html .= '<input type="hidden" name="background" id="background" value="'.esc_attr($value).'"/>';
        return $html;

     }
     
     public static function loadThemeColor($value=''){
         $path = self::path('theme') . '/css/colors/';
         $files = self::folders($path);
         $html='';
        if (isset ( $files ) && count ( $files )) {
                $i = 0;
                foreach ( $files as $val ) {
                        $cls = '';
                        if($val == 'custom') continue;
                        if ($val == $value || ($value == '' && $i == 0)) {
                                $cls = 'active';
                        }
                        $i ++;

                        $html .='<span title="'.esc_attr(str_replace('_', ' ', $val)).'" class="color-list ' . esc_attr($cls) . '" style="background: url(\'' . self::urls('theme') . "css/colors/{$val}/thumbnail.jpg" . '\') no-repeat center">'.$val.'</span>';
                }	
        }
        $html .= '<input type="hidden" name="themecolor" id="themecolor" value="'.esc_attr($value).'"/>';	
        return $html;
         
     }

    public static function encodeOpiton($data){
         $encode_optioin = serialize($data);
         $jvfn = 'base64_'.'encode';
         $encode_optioin = $jvfn($encode_optioin);
         return $encode_optioin;
     }
     
     public static function decodeOpiton($data){
         $jvfn = 'base64_'.'decode';
         $decode_optioin = $jvfn($data);
         $decode_optioin = unserialize($decode_optioin);
         return $decode_optioin;
     }
    
     public static function fileSystem(){
        global $wp_filesystem;
        // Is a filesystem accessor setup?
        if ( ! $wp_filesystem || ! is_object( $wp_filesystem ) ) {
                self::import( ABSPATH . 'wp-admin/includes/file.php' );
                WP_Filesystem();
        }
    }
    /* LIBRARY FILES && FOLDER */
    
    public static function folders($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'),
        $excludefilter = array('^\..*')) {
        // Is the path a folder?
        if (!is_dir($path)) return false;
        // Compute the excludefilter string
        if (count($excludefilter)) {
                $excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
        } else {
                $excludefilter_string = '';
        }
        // Get the folders
        $arr = self::_items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, false);
        // Sort the folders
        asort($arr);
        return array_values($arr);
	}
     

     /* library */
     public static function files($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'),
        $excludefilter = array('^\..*', '.*~'), $naturalSort = false) {
        // Is the path a folder?
        if (!is_dir($path)) return false;
        // Compute the excludefilter string
        if (count($excludefilter)) {
                $excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
        } else {
                $excludefilter_string = '';
        }
        // Get the files
        $arr = self::_items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, true);
        // Sort the files based on either natural or alpha method
        if ($naturalSort) {
                natsort($arr);
        } else {
                asort($arr);
        }
        return array_values($arr);
	}
     
     
        protected static function _items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles) {
            @set_time_limit(ini_get('max_execution_time'));
            $arr = array();
            // Read the source directory
            if (!($handle = @opendir($path))) {
                    return $arr;
            }
            while (($file = readdir($handle)) !== false)
            {
                    if ($file != '.' && $file != '..' && !in_array($file, $exclude)
                            && (empty($excludefilter_string) || !preg_match($excludefilter_string, $file)))
                    {
                            // Compute the fullpath
                            $fullpath = $path . '/' . $file;

                            // Compute the isDir flag
                            $isDir = is_dir($fullpath);

                            if (($isDir xor $findfiles) && preg_match("/$filter/", $file))
                            {
                                    // (fullpath is dir and folders are searched or fullpath is not dir and files are searched) and file matches the filter
                                    if ($full)
                                    {
                                            // Full path is requested
                                            $arr[] = $fullpath;
                                    }
                                    else
                                    {
                                            // Filename is requested
                                            $arr[] = $file;
                                    }
                            }

                            if ($isDir && $recurse)
                            {
                                    // Search recursively
                                    if (is_int($recurse))
                                    {
                                            // Until depth 0 is reached
                                            $arr = array_merge($arr, self::_items($fullpath, $filter, $recurse - 1, $full, $exclude, $excludefilter_string, $findfiles));
                                    }
                                    else
                                    {
                                            $arr = array_merge($arr, self::_items($fullpath, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles));
                                    }
                            }
                    }
            }
            closedir($handle);
            return $arr;
	}
        
        public static function jcopy($src, $dest, $path = '', $force = false, $use_streams = false)
	{
            @set_time_limit(ini_get('max_execution_time'));

            // Eliminate trailing directory separators, if any
            $src = rtrim($src, DIRECTORY_SEPARATOR);
            $dest = rtrim($dest, DIRECTORY_SEPARATOR);

            if (!self::exists($src))
            {
                    throw new RuntimeException('Source folder not found', -1);
            }
            if (self::exists($dest) && !$force)
            {
                    throw new RuntimeException('Destination folder already exists', -1);
            }

            // Make sure the destination exists
            if (!self::create($dest))
            {
                    throw new RuntimeException('Cannot create destination folder', -1);
            }


            if (!($dh = @opendir($src)))
            {
                    throw new RuntimeException('Cannot open source folder', -1);
            }
            // Walk through the directory copying files and recursing into folders.
            while (($file = readdir($dh)) !== false)
            {
                    $sfid = $src . '/' . $file;
                    $dfid = $dest . '/' . $file;

                    switch (filetype($sfid))
                    {
                            case 'dir':
                                    if ($file != '.' && $file != '..')
                                    {
                                            $ret = self::jcopy($sfid, $dfid, null, $force, $use_streams);

                                            if ($ret !== true)
                                            {
                                                    return $ret;
                                            }
                                    }
                                    break;

                            case 'file':

                                    if (!@copy($sfid, $dfid))
                                    {
                                            throw new RuntimeException('Copy file failed', -1);
                                    }

                                    break;
                    }

            }
            return true;
	}
        
        public static function clean($path, $ds = DIRECTORY_SEPARATOR)
	{
		if (!is_string($path) && !empty($path))
		{
			throw new UnexpectedValueException('JVLibrary::clean: $path is not a string.');
		}

		$path = trim($path);

		if (empty($path))
		{
			$path = self::path('root');
		}
		// Remove double slashes and backslashes and convert all slashes and backslashes to DIRECTORY_SEPARATOR
		// If dealing with a UNC path don't forget to prepend the path with a backslash.
		elseif (($ds == '\\') && ($path[0] == '\\' ) && ( $path[1] == '\\' ))
		{
			$path = "\\" . preg_replace('#[/\\\\]+#', $ds, $path);
		}
		else
		{
			$path = preg_replace('#[/\\\\]+#', $ds, $path);
		}

		return $path;
	}
        
        public static function exists($path)
	{
		return is_dir(self::clean($path));
	}
        
        public static function create($path = '', $mode = 0755)
	{
		
		static $nested = 0;

		
		// Check if parent dir exists
		$parent = dirname($path);

		if (!self::exists($parent))
		{
			// Prevent infinite loops!
			$nested++;

			if (($nested > 20) || ($parent == $path))
			{
				return false;
			}

			// Create the parent directory
			if (self::create($parent, $mode) !== true)
			{
				// JFolder::create throws an error
				$nested--;

				return false;
			}

			// OK, parent directory has been created
			$nested--;
		}

		// Check if dir already exists
		if (self::exists($path))
		{
			return true;
		}
        }
        
 }
 
