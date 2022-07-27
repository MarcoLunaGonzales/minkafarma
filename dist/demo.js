const menuFarma = new Mmenu(
                document.querySelector('#menu'),
                {
                    extensions: ['theme-dark', 'shadow-page'],
                    setSelected: true,
                    counters: true,
                    searchfield: {
                        placeholder: 'Buscar',
                        placeholder: 'Buscar',
                    },
                    iconbar: {
                        use: '(min-width: 450px)',
                        top: [
                            '<a href="#/"><span class="material-icons">menu</span></a>',
                            
                        ],
                    },
                    sidebar: {
                        collapsed: {
                            use: '(min-width: 450px)',
                            hideNavbar: false,
                        },
                        expanded: {
                            use: '(min-width: 992px)',
                        },
                    },
                    navbars: [
                        {
                            content: ['searchfield'],
                        },
                        {
                            type: 'tabs',
                            content: [
                                '<a href="#panel-menu"><span>OPCIONES</span></a>','breadcrumbs', 'close'
                                
                            ],
                        },
                        {
                            position: 'bottom',
                            content: [
                                '<a href="" target="_blank">Sistema Comercial</a>',
                            ],
                        },
                    ],
                },
                {
                    searchfield: {
                        clear: true,
                    },
                    navbars: {
                        breadcrumbs: {
                            removeFirst: true,
                        },
                    },
                }
            );

            document.addEventListener('click', function (evnt) {
                var anchor = evnt.target.closest('a[href^="#/"]');
                if (anchor) {
                    //alert("Thank you for clicking, but that's a demo link.");
                    evnt.preventDefault();
                }
            });
$(document).ready(function(){
      var height = $(window).height();
      $('.content').height(height);
});
