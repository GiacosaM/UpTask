!function(){!async function(){const t=window.location.origin;try{const o=i();console.log("El proyecto es es "+o),console.log(typeof o);const n=`${t}/api/tareas?id=${o}`;console.log("la URL es "+n);const r=await fetch(n);console.log(r);const c=await r.json();console.log("El resultado es "+c),e=c.tareas,a()}catch(e){console.log("el error es "+e)}}();let e=[],t=[];document.querySelector("#agregar-tarea").addEventListener("click",(function(){n(!1)}));function o(o){const n=o.target.value;t=""!==n?e.filter(e=>e.estado===n):[],a()}function a(){!function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}(),function(){const t=e.filter(e=>"0"===e.estado),o=document.querySelector("#pendientes");0===t.length?o.disabled=!0:o.disabled=!1}(),function(){const t=e.filter(e=>"1"===e.estado),o=document.querySelector("#completadas");0===t.length?o.disabled=!0:o.disabled=!1}();const o=t.length?t:e;if(0===o.length){const e=document.querySelector("#listado-tareas"),t=document.createElement("LI");return t.textContent="No Hay Tareas",t.classList.add("no-tareas"),void e.appendChild(t)}const r={0:"Pendiente",1:"Completa"};o.forEach(t=>{const o=document.createElement("LI");o.dataset.tareaId=t.id,o.classList.add("tarea");const s=document.createElement("P");s.textContent=t.nombre,s.onclick=function(){n(!0,{...t})};const d=document.createElement("DIV");d.classList.add("opciones");const l=document.createElement("BUTTON");l.classList.add("estado-tarea"),l.classList.add(""+r[t.estado].toLowerCase()),l.textContent=r[t.estado],l.dataset.estadoTarea=t.estado,l.onclick=function(){!function(e){const t="1"===e.estado?"0":"1";e.estado=t,c(e)}({...t})};const u=document.createElement("BUTTON");u.classList.add("eliminar-tarea"),u.dataset.idTarea=t.id,u.textContent="Eliminar",u.onclick=function(){!function(t){Swal.fire({title:"Eliminar Tarea?",showCancelButton:!0,confirmButtonText:"Si",cancelButtonText:"No"}).then(o=>{o.isConfirmed&&async function(t){const o=window.location.origin,{estado:n,id:r,nombre:c}=t,s=new FormData;s.append("id",r),s.append("nombre",c),s.append("estado",n),s.append("proyectoid",i());try{const n=o+"/api/tareas/eliminar",r=await fetch(n,{method:"POST",body:s}),c=await r.json();c.resultado&&(Swal.fire("Eliminado",c.mensaje,"success"),e=e.filter(e=>e.id!==t.id),a())}catch(e){}}(t)})}({...t})},d.appendChild(l),d.appendChild(u),o.appendChild(s),o.appendChild(d);document.querySelector("#listado-tareas").appendChild(o)})}function n(t=!1,o={}){const n=document.createElement("DIV");n.classList.add("modal"),n.innerHTML=`\n            <form class="formulario nueva-tarea">\n                <legend>${t?"Editar Tarea":"Añade una nueva Tarea"}</legend>\n                <div class="campo"> \n                <label>Tarea</label>\n                <input\n                    type="text"\n                    name="tarea"\n                    placeholder="${o.nombre?"Edita la tarea":"Añadir Tarea al proyecto Actual"}"\n                    id="tarea"\n                    value="${o.nombre?o.nombre:""}"\n                    />\n                </div>\n                <div class="opciones">\n                    <input \n                        type="submit"\n                        class="submit-nueva-tarea" \n                        value="${o.nombre?"Guardar Cambios":"Añadir Tarea"}"\n                    />\n                    <button type="button" class="cerrar-modal">Cancelar</button>\n                    \n                </div>\n            </form>\n            `,setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},0),n.addEventListener("click",(function(s){if(s.preventDefault(),s.target.classList.contains("cerrar-modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout(()=>{n.remove()},500)}if(s.target.classList.contains("submit-nueva-tarea")){const n=document.querySelector("#tarea").value.trim();if(""===n)return void r("El nombre de la tarea es Obligatorio","error",document.querySelector(".formulario legend"));t?(o.nombre=n,c(o)):async function(t){const o=window.location.origin,n=new FormData;n.append("nombre",t),n.append("proyectoid",i());try{const c=o+"/api/tarea";console.log(c);const i=await fetch(c,{method:"POST",body:n}),s=await i.json();if(r(s.mensaje,s.tipo,document.querySelector(".formulario legend")),"exito"===s.tipo){const o=document.querySelector(".modal");setTimeout(()=>{o.remove()},200);const n={id:String(s.id),nombre:t,estado:"0",proyectoid:s.proyectoid};e=[...e,n],a()}}catch(e){console.log("error 1")}}(n)}})),document.querySelector(".dashboard").appendChild(n)}function r(e,t,o){const a=document.querySelector(".alerta");a&&a.remove();const n=document.createElement("DIV");n.classList.add("alerta",t),n.textContent=e,o.parentElement.insertBefore(n,o.nextElementSibling),setTimeout(()=>{n.remove()},3e3)}async function c(t){const o=window.location.origin,{estado:n,id:r,nombre:c,proyectoid:s}=t,d=new FormData;d.append("id",r),d.append("nombre",c),d.append("estado",n),d.append("proyectoid",i());try{const t=o+"/api/tareas/actualizar",i=await fetch(t,{method:"POST",body:d}),s=await i.json();if("exito"===s.respuesta.tipo){swal.fire(s.respuesta.mensaje,s.respuesta.mensaje,"success");const t=document.querySelector(".modal");t&&t.remove(),e=e.map(e=>(e.id===r&&(e.estado=n,e.nombre=c),e)),a()}}catch(e){}}function i(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).id}document.querySelectorAll('#filtros input[type="radio"]').forEach(e=>{e.addEventListener("input",o)})}();