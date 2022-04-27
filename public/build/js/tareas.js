!function(){!async function(){const t=window.location.origin;try{const a=c(),o=`${t}/api/tareas?id=${a}`;console.log("la URL es "+o);const r=await fetch(o),i=await r.json();e=i.tareas,n()}catch(e){console.log("el error es "+e)}}();let e=[],t=[];document.querySelector("#agregar-tarea").addEventListener("click",(function(){o(!1)}));function a(a){const o=a.target.value;t=""!==o?e.filter(e=>e.estado===o):[],n()}function n(){!function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}(),function(){const t=e.filter(e=>"0"===e.estado),a=document.querySelector("#pendientes");0===t.length?a.disabled=!0:a.disabled=!1}(),function(){const t=e.filter(e=>"1"===e.estado),a=document.querySelector("#completadas");0===t.length?a.disabled=!0:a.disabled=!1}();const a=t.length?t:e;if(0===a.length){const e=document.querySelector("#listado-tareas"),t=document.createElement("LI");return t.textContent="No Hay Tareas",t.classList.add("no-tareas"),void e.appendChild(t)}const r={0:"Pendiente",1:"Completa"};a.forEach(t=>{const a=document.createElement("LI");a.dataset.tareaId=t.id,a.classList.add("tarea");const s=document.createElement("P");s.textContent=t.nombre,s.onclick=function(){o(!0,{...t})};const d=document.createElement("DIV");d.classList.add("opciones");const l=document.createElement("BUTTON");l.classList.add("estado-tarea"),l.classList.add(""+r[t.estado].toLowerCase()),l.textContent=r[t.estado],l.dataset.estadoTarea=t.estado,l.onclick=function(){!function(e){const t="1"===e.estado?"0":"1";e.estado=t,i(e)}({...t})};const u=document.createElement("BUTTON");u.classList.add("eliminar-tarea"),u.dataset.idTarea=t.id,u.textContent="Eliminar",u.onclick=function(){!function(t){Swal.fire({title:"Eliminar Tarea?",showCancelButton:!0,confirmButtonText:"Si",cancelButtonText:"No"}).then(a=>{a.isConfirmed&&async function(t){const a=window.location.origin,{estado:o,id:r,nombre:i}=t,s=new FormData;s.append("id",r),s.append("nombre",i),s.append("estado",o),s.append("proyectoid",c());try{const o=a+"/api/tareas/eliminar",r=await fetch(o,{method:"POST",body:s}),i=await r.json();i.resultado&&(Swal.fire("Eliminado",i.mensaje,"success"),e=e.filter(e=>e.id!==t.id),n())}catch(e){}}(t)})}({...t})},d.appendChild(l),d.appendChild(u),a.appendChild(s),a.appendChild(d);document.querySelector("#listado-tareas").appendChild(a)})}function o(t=!1,a={}){const o=document.createElement("DIV");o.classList.add("modal"),o.innerHTML=`\n            <form class="formulario nueva-tarea">\n                <legend>${t?"Editar Tarea":"Añade una nueva Tarea"}</legend>\n                <div class="campo"> \n                <label>Tarea</label>\n                <input\n                    type="text"\n                    name="tarea"\n                    placeholder="${a.nombre?"Edita la tarea":"Añadir Tarea al proyecto Actual"}"\n                    id="tarea"\n                    value="${a.nombre?a.nombre:""}"\n                    />\n                </div>\n                <div class="opciones">\n                    <input \n                        type="submit"\n                        class="submit-nueva-tarea" \n                        value="${a.nombre?"Guardar Cambios":"Añadir Tarea"}"\n                    />\n                    <button type="button" class="cerrar-modal">Cancelar</button>\n                    \n                </div>\n            </form>\n            `,setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},0),o.addEventListener("click",(function(s){if(s.preventDefault(),s.target.classList.contains("cerrar-modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout(()=>{o.remove()},500)}if(s.target.classList.contains("submit-nueva-tarea")){const o=document.querySelector("#tarea").value.trim();if(""===o)return void r("El nombre de la tarea es Obligatorio","error",document.querySelector(".formulario legend"));t?(a.nombre=o,i(a)):async function(t){const a=window.location.origin,o=new FormData;o.append("nombre",t),o.append("proyectoid",c());try{const i=a+"/api/tarea";console.log(i);const c=await fetch(i,{method:"POST",body:o}),s=await c.json();if(r(s.mensaje,s.tipo,document.querySelector(".formulario legend")),"exito"===s.tipo){const a=document.querySelector(".modal");setTimeout(()=>{a.remove()},200);const o={id:String(s.id),nombre:t,estado:"0",proyectoid:s.proyectoid};e=[...e,o],n()}}catch(e){console.log("error 1")}}(o)}})),document.querySelector(".dashboard").appendChild(o)}function r(e,t,a){const n=document.querySelector(".alerta");n&&n.remove();const o=document.createElement("DIV");o.classList.add("alerta",t),o.textContent=e,a.parentElement.insertBefore(o,a.nextElementSibling),setTimeout(()=>{o.remove()},3e3)}async function i(t){const a=window.location.origin,{estado:o,id:r,nombre:i,proyectoid:s}=t,d=new FormData;d.append("id",r),d.append("nombre",i),d.append("estado",o),d.append("proyectoid",c());try{const t=a+"/api/tareas/actualizar",c=await fetch(t,{method:"POST",body:d}),s=await c.json();if("exito"===s.respuesta.tipo){swal.fire(s.respuesta.mensaje,s.respuesta.mensaje,"success");const t=document.querySelector(".modal");t&&t.remove(),e=e.map(e=>(e.id===r&&(e.estado=o,e.nombre=i),e)),n()}}catch(e){}}function c(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).id}document.querySelectorAll('#filtros input[type="radio"]').forEach(e=>{e.addEventListener("input",a)})}();