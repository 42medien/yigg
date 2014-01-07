Event.observe(window,'load',function(){ NinjaCommander=new NinjaCommander(); });

$$('a[rel="external"]').each(function (link) {
    if (link.readAttribute('href') != '' && link.readAttribute('href') != '#') {
        link.writeAttribute('target', '_blank');
    }
});