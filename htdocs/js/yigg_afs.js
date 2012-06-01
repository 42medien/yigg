     /*
      * This function retrieves the search query from the URL.
      */

      function GetParam()
      {
         var match = new RegExp("q=(.+)[&]","i").exec(unescape(location.search));

         if (match==null)
         {
             match = new RegExp(name + "=(.+)","i").exec(location.search);
         }

          if (match==null)
          {
            return null;
          }
          match = match + "";
          result = match.split(",");
          return result[1];
      }


      /*
       * This function is required. It processes the google_ads JavaScript object,
       * which contains AFS ads relevant to the user's search query. The name of
       * this function <i>must</i> be <b>google_afs_request_done</b>. If this
       * function is not named correctly, your page will not display AFS ads.
       */

      function google_afs_request_done(google_ads)
      {
          /*
           * Verify that there are actually ads to display.
           */
          var google_num_ads = google_ads.length;
          if (google_num_ads <= 0)
          {
              return;
          }

          var wideAds = '<div id="wide_ad_unit">';   // wide ad unit html text

          for(i = 0; i < google_num_ads; i++)
          {
              if (google_ads[i].type=="text/wide")
              {
                  // render a wide ad
                  wideAds+='<div class="wideAd nodes'+ google_num_ads +'">' + '<p class="ad_line1">' + '<a onmouseover="javascript:window.status=\'' +
                          google_ads[i].url + '\';return true;" ' +
                          'onmouseout="javascript:window.status=\'\';return true;" ' +
                          'href="' + google_ads[i].url + '">' +
                          google_ads[i].line1 + '</a></p>' +
                          '<p class="ad_text">' + google_ads[i].line2 + '</p>' +
                          '<p class="ad_url"><a onmouseover="javascript:window.status=\'' +
                          google_ads[i].url + '\';return true;" ' +
                          'onmouseout="javascript:window.status=\'\';return true;" ' +
                          'href="' + google_ads[i].url + '">' + google_ads[i].visible_url + '</a></p></div>';
              }
          }

          if (wideAds != "")
          {
              wideAds = '<a style="text-decoration:none" ' +
                        'href="https://www.google.com/adsense/support/bin/request.py?contact=afs_violation">' +
                        '<div class="ad_header" style="text-align:left">Ads by Google</div></a>' + wideAds + "</div>";
          }

          // Write HTML for wide and narrow ads to the proper <div> elements
          document.getElementById("wide_ad_unit_place").innerHTML = wideAds;
      }

      google_afs_query = GetParam();
      google_afs_ad = 'w3'; // specify the number of ads you are requesting
      google_afs_client = 'pub-1406192967534280'; // substitute your client ID
      google_afs_channel = '8430346320'; // enter your custom channel ID
      google_afs_hl = 'de'; // enter your interface language if not English
      google_afs_ie = 'utf8'; // select input encoding scheme
      google_afs_oe = 'utf8'; // select output encoding scheme