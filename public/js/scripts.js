!function(w, d){
  
  var handlers = {

    'toggle': function(e) {
      var el = document.getElementById(this.hash.substr(1));
      if (el) {
        e.preventDefault();
        var 
          wasActive = this.hash == w.location.hash || el.classList.contains('active'),
          addRemove = wasActive ? 'remove' : 'add',
          tabs = container = _closest(this, '.tabs');

        if (tabs && !wasActive) {
          var els = tabs.querySelectorAll('.active');
          for (var i = 0; i < els.length; i++) {
            els[i].classList.remove('active');
          }
          
        }
        
        el.classList[addRemove]('active');
        this.classList[addRemove]('active');
        var url = document.location.href.split('#')[0];
        if (history.replaceState) {
          w.location.hash = '_';
          history.replaceState({id: url}, d.title, wasActive ? url : url + this.hash);
        }
        
      }
    },
    
    'scroll': function(e){
      var target = document.getElementById(this.hash.substr(1));
      if (!target) return;
      e.preventDefault();
      _scrollTo(target);
    },
    
    'multistatus': function(e){
      e.preventDefault();
      var 
        values = this.hash.substr(1).split(','),
        container = _closest(this, 'ul'),
        form = _closest(this, 'form');
      
      
      var els = container.querySelectorAll('.active');
      for (var i = 0; i < els.length; i++) {
        els[i].classList.remove('active');
      }
      this.classList.add('active');
      
      var els = form.querySelectorAll('#search_dossier_form_status input');
      for (var i = 0; i < els.length; i++) {
        els[i].checked = false;
      }
      
      
      for (var i = 0; i < values.length; i++) {
        var el = form.querySelector('input[value="' + values[i] + '"]');
        if (el) {
          el.checked = true;
        }
      }
      
      helpers.trigger(form, 'submit');
      
    },
    
    'add-file': function(e){
      e && e.preventDefault();
      
      var 
        files = _closest(this, '.files-container'),
        counter = files.dataset.counter || -1,
        prototype = files.querySelector('.prototype').cloneNode(true);
        
      
      counter++;
    
      prototype.classList.remove('prototype');
      prototype.setAttribute('data-name', prototype.getAttribute('data-name').replace('__name__', 'n' + counter));
      prototype.querySelector('input[type="text"]').setAttribute('name', prototype.querySelector('input[type="text"]').getAttribute('name').replace('__name__', 'n' + counter));
      prototype.querySelector('input[type="file"]').setAttribute('name', prototype.querySelector('input[type="file"]').getAttribute('name').replace('__name__', 'n' + counter));
      files.insertBefore(prototype, this);
    
      window.schuldhulp.pdfSplitter.dragula.containers.push(prototype.querySelector('.drop-area'));
    
      prototype.addEventListener('filled', function (event) {
          var elm = prototype.querySelector('input[type="text"]');
          if (elm.value === '' || elm.value === null) {
              elm.value = prototype.parentNode.getAttribute('data-default-document-naam');
              elm.focus();
          }
      });
    
      prototype.querySelector('input[type="file"]').addEventListener('change', function (event) {
          if (event.target.value) {
              if (prototype.querySelector('.drop-area')) {
                  prototype.removeChild(prototype.querySelector('.drop-area'));
              }
              prototype.classList.add('files-only');
              var button = prototype.querySelector('.file');
              button.textContent = event.target.value.replace('/', '\\').split('\\').pop();
            
              var event = new Event('filled');
              prototype.dispatchEvent(event);
          }
      });
    
      files.dataset.counter = counter;
      
      return prototype;
    }
    
  };
  
  var decorators = {
    
    'droppable': function(){
      var
        container = this;
        // div = document.createElement('div');
      // if (!(('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window) return;
      
      
      // container.class
      
      var currentZone;
      
      var _reset = function(e){
        e.preventDefault();
        e.stopPropagation();
      }
      var _over = function(e){
        var zone = (e && e.target) && _closest(e.target, '.accordion');
        if (!zone) return;
        if (currentZone != zone) {
          if (currentZone) currentZone.classList.remove('drop-over');
          currentZone = zone;
          zone.classList.add('drop-over');
        }
      };
      var _out = function(e){
        
        // var zone = (e && e.target) && _closest(e.target, '.accordion');
        // if (!zone) return;
        // zone.classList.remove('drop-over');
      };
      var _drop = function(e){
        
        if (currentZone) currentZone.classList.remove('drop-over');
        
        var files = e.dataTransfer.files;
        var zone = (e && e.target) && _closest(e.target, '.accordion');
        var form = (e && e.target) && _closest(e.target, 'form');
        if (!files) return;
        
        zone.classList.add('active');
        
        // create new file zone
        for (var i=0; i<files.length; i++) {
          var addButton = zone.querySelector('.files-container .add.bestand');
          if (addButton) {
            var 
              file = handlers['add-file'].call(addButton),
              input = file.querySelector('[type="file"]'),
              button = file.querySelector('.file'),
              icon = file.querySelector('[data-extension]');
            
            button.textContent = files[i].name.replace('/', '\\').split('\\').pop();
            // icon.dataset.extension = files[i].type.split('/').pop();
            
            var event = new Event('filled');
            file.dispatchEvent(event);
            
            form.files = form.files || [];
            form.files.push({
              'name': input.name,
              'file': files[i]
            });
            input.parentNode.removeChild(input);
          }
          
        }
        
        
        
        helpers.trigger(form, 'change');
        
      };
      
      if (!w.dropReset) {
        w.addEventListener('drop', _reset);
        w.addEventListener('dragover', _reset);
        w.dropReset = true;
      }

      var events = 'dragenter dropstart'.split(' ');
      for (var i = 0; i < events.length; i++) {
        this.addEventListener(events[i], _over);
      }

      var events = 'dragleave dropend  drop'.split(' ');
      for (var i = 0; i < events.length; i++) {
        this.addEventListener(events[i], _out);
      }

      this.addEventListener('drop', _drop);

    },
    
    
  };
  
  var submitters = {
    
    'save': function(e){
      var 
        form = this;
        
      e && e.preventDefault();
      if (form.request) form.request.abort();

      form.classList.add('in-progress');
    
      var data = new FormData(form);
      
      var url = form.action + '?v' + (new Date()).getTime();
      
      if (form.method.toLowerCase() == 'get') {
        var parameters = []
        for (var pair of data.entries()) {
          parameters.push(
            encodeURIComponent(pair[0]) + '=' +
            encodeURIComponent(pair[1])
          );
        }
        url += '&' + parameters.join('&');
        data = {};
      };
      
      if (form.files) {
        for (var i=0; i< form.files.length; i++) {
          data.append(form.files[i].name, form.files[i].file);
        }
      }
      form.files = [];
      
      form.request = helpers.ajax({
        type: form.method,
        url: url,
        data: data,
        callback: function(data){

          var div = document.createElement('div');
          div.innerHTML = data;
        
          var 
            result = div.querySelector(form.dataset.resultSelector),
            target = d.querySelector(form.dataset.resultSelector);
          if (result && target) {
            d.querySelector(form.dataset.resultSelector).innerHTML = result.innerHTML;
          }
        
          form.classList.remove('in-progress');
          form.classList.remove('form-changed');

        },
        error: function(){
          form.classList.remove('in-progress');
          form.classList.add('ajax-error');
        }
      });
      
    }
    
  };
  
  var changers = {
    'change': function(){
      var 
        form = this;
        
        
      form.classList.add('form-changed');
      
      // if (!form.changed) {
      //   form.changed = true;
        // w.onbeforeunload = function() {
        //   return 'Je hebt nog niet opgeslagen wijzigingen. Deze zul verloren gaan als je niet eerst je wijzigingen opslaat';
        // }
      // }
      
      // if (form.files) {
      //
      //   var proto = form.querySelector('.file-container.prototype[data-name]');
      //
      //   for (var i = 0; i < form.files.length; i++) {
      //     data.append(proto.dataset.name.replace('[__name__][file]', '[n' + i + '][file]'), form.files[i]);
      //     data.append(proto.dataset.name.replace('[__name__][file]', '[n' + i + '][naam]'), proto.parentNode.dataset.defaultDocumentNaam + (i > 0 ? ' ' + i : ''));
      //
      //   }
      //   form.files = false;
      // }
      

    },
    
  };
  
  var helpers = {
    ajax: function(options) {
      var request = new XMLHttpRequest();
      request.open(options.type, options.url, true);
      request.onreadystatechange = function () {
        if (request.readyState == 4) {
          if (request.status >= 200 && request.status < 400) {
            if (options.callback && typeof (options.callback) == 'function') {
              options.callback.call(request, request.responseText);
            }
          } else {
            if (options.error && typeof (options.error) == 'function') {
              options.error.call(request, request.responseText);
            }
          }
        }
      };

      request.send(options.data);
      
      return request;
    },
    
    'trigger': function(el, eventType){
      var e = document.createEvent('MouseEvents');
      e.initMouseEvent(eventType, true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
      el.dispatchEvent(e);
    }
    
  };
  
  if (w.location.hash) {
    var 
      el = document.querySelector('.document ' + w.location.hash),
      trigger = document.querySelector('[data-handler*="toggle"][href^="' + w.location.hash + '"]');
    
    if (el) {
      
      if (trigger) {
        trigger.classList.add('active');
        el.classList.add('active');
      }

      setTimeout(function(){
        w.scrollTo(0,0);
        setTimeout(function(){
          _scrollTo(el);
        }, 800);
      },1);
    }
  }
  
  
  d.addEventListener('click',function(t){var k,e,a=t&&t.target;if(a=_closest(a,'[data-handler]')){var r=a.getAttribute('data-handler').split(/\s+/);if('A'==a.tagName&&(t.metaKey||t.shiftKey||t.ctrlKey||t.altKey))return;for(e=0;e<r.length;e++){k=r[e].split(/[\(\)]/);handlers[k[0]]&&handlers[k[0]].call(a,t,k[1])}}});
  
  d.addEventListener('submit',function(t){var k,e,f=t&&t.target;if(f=_closest(f,'[data-submitter]')){var r=f.getAttribute('data-submitter').split(/\s+/);for(e=0;e<r.length;e++){k=r[e].split(/[\(\)]/);submitters[k[0]]&&submitters[k[0]].call(f,t,k[1])}}});

  var _change = function(t){var k,e,c=t&&t.target;if(c=_closest(c,'[data-changer]')){var r=c.getAttribute('data-changer').split(/\s+/);for(e=0;e<r.length;e++){k=r[e].split(/[\(\)]/);changers[k[0]]&&changers[k[0]].call(c,t,k[1])}}};

  d.addEventListener('change', _change);
  // d.addEventListener('keyup', _change);

  var scrollers=[];w.addEventListener('scroll',function(){requestAnimationFrame(function(){for(var l=0;l<scrollers.length;l++)scrollers[l].el&&scrollers[l].fn.call(scrollers[l].el)})},!1);
  
  var _scrollTo=function(n,o){var e,i=window.pageYOffset,t=window.pageYOffset+n.getBoundingClientRect().top,r=(document.body.scrollHeight-t<window.innerHeight?document.body.scrollHeight-window.innerHeight:t)-i,w=function(n){return n<.5?4*n*n*n:(n-1)*(2*n-2)*(2*n-2)+1},o=o||1e3;r&&window.requestAnimationFrame(function n(t){e||(e=t);var d=t-e,a=Math.min(d/o,1);a=w(a),window.scrollTo(0,i+r*a),d<o&&window.requestAnimationFrame(n)})};

  var _decorate = function(){var k,i,j,decoratorString,el,els=d.querySelectorAll('[data-decorator]');for(i=0;i<els.length;i++){for(decoratorString=(el=els[i]).getAttribute('data-decorator').split(/\s+/),j=0;j<decoratorString.length;j++){k=decoratorString[j].split(/[\(\)]/);decorators[k[0]]&&decorators[k[0]].call(el,k[1]);el.removeAttribute('data-decorator')}}};

  var _closest=function(e,t){var ms='MatchesSelector',c;['matches','webkit'+ms,'moz'+ms,'ms'+ms,'o'+ms].some(function(e){return'function'==typeof document.body[e]&&(c=e,!0)});var r=e;try{for(;e;){if(r&&r[c](t))return r;e=r=e.parentElement}}catch(e){}return null};
  
  _decorate();

}(window, document.documentElement);