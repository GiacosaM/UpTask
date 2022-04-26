!function(){!async function(){try{const t=i(),a=`${server}/api/tareas?id=${t}`,o=await fetch(a),r=await o.json();e=r.tareas,n()}catch(e){console.log("error 0")}}();let e=[],t=[];document.querySelector("#agregar-tarea").addEventListener("click",(function(){o(!1)}));function a(a){const o=a.target.value;t=""!==o?e.filter(e=>e.estado===o):[],n()}function n(){!function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}(),function(){const t=e.filter(e=>"0"===e.estado),a=document.querySelector("#pendientes");0===t.length?a.disabled=!0:a.disabled=!1}(),function(){const t=e.filter(e=>"1"===e.estado),a=document.querySelector("#completadas");0===t.length?a.disabled=!0:a.disabled=!1}();const a=t.length?t:e;if(0===a.length){const e=document.querySelector("#listado-tareas"),t=document.createElement("LI");return t.textContent="No Hay Tareas",t.classList.add("no-tareas"),void e.appendChild(t)}const r={0:"Pendiente",1:"Completa"};a.forEach(t=>{const a=document.createElement("LI");a.dataset.tareaId=t.id,a.classList.add("tarea");const s=document.createElement("P");s.textContent=t.nombre,s.onclick=function(){o(!0,{...t})};const d=document.createElement("DIV");d.classList.add("opciones");const l=document.createElement("BUTTON");l.classList.add("estado-tarea"),l.classList.add(""+r[t.estado].toLowerCase()),l.textContent=r[t.estado],l.dataset.estadoTarea=t.estado,l.onclick=function(){!function(e){const t="1"===e.estado?"0":"1";e.estado=t,c(e)}({...t})};const u=document.createElement("BUTTON");u.classList.add("eliminar-tarea"),u.dataset.idTarea=t.id,u.textContent="Eliminar",u.onclick=function(){!function(t){Swal.fire({title:"Eliminar Tarea?",showCancelButton:!0,confirmButtonText:"Si",cancelButtonText:"No"}).then(a=>{a.isConfirmed&&async function(t){const{estado:a,id:o,nombre:r}=t,c=new FormData;c.append("id",o),c.append("nombre",r),c.append("estado",a),c.append("proyectoid",i());try{const a=server+"/api/tareas/eliminar",o=await fetch(a,{method:"POST",body:c}),r=await o.json();r.resultado&&(Swal.fire("Eliminado",r.mensaje,"success"),e=e.filter(e=>e.id!==t.id),n())}catch(e){}}(t)})}({...t})},d.appendChild(l),d.appendChild(u),a.appendChild(s),a.appendChild(d);document.querySelector("#listado-tareas").appendChild(a)})}function o(t=!1,a={}){const o=document.createElement("DIV");o.classList.add("modal"),o.innerHTML=`\n            <form class="formulario nueva-tarea">\n                <legend>${t?"Editar Tarea":"Añade una nueva Tarea"}</legend>\n                <div class="campo"> \n                <label>Tarea</label>\n                <input\n                    type="text"\n                    name="tarea"\n                    placeholder="${a.nombre?"Edita la tarea":"Añadir Tarea al proyecto Actual"}"\n                    id="tarea"\n                    value="${a.nombre?a.nombre:""}"\n                    />\n                </div>\n                <div class="opciones">\n                    <input \n                        type="submit"\n                        class="submit-nueva-tarea" \n                        value="${a.nombre?"Guardar Cambios":"Añadir Tarea"}"\n                    />\n                    <button type="button" class="cerrar-modal">Cancelar</button>\n                    \n                </div>\n            </form>\n            `,setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},0),o.addEventListener("click",(function(s){if(s.preventDefault(),s.target.classList.contains("cerrar-modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout(()=>{o.remove()},500)}if(s.target.classList.contains("submit-nueva-tarea")){const o=document.querySelector("#tarea").value.trim();if(""===o)return void r("El nombre de la tarea es Obligatorio","error",document.querySelector(".formulario legend"));t?(a.nombre=o,c(a)):async function(t){const a=new FormData;a.append("nombre",t),a.append("proyectoid",i());try{const o=server+"/api/tarea";console.log(o);const c=await fetch(o,{method:"POST",body:a}),i=await c.json();if(r(i.mensaje,i.tipo,document.querySelector(".formulario legend")),"exito"===i.tipo){const a=document.querySelector(".modal");setTimeout(()=>{a.remove()},200);const o={id:String(i.id),nombre:t,estado:"0",proyectoid:i.proyectoid};e=[...e,o],n()}}catch(e){console.log("error 1")}}(o)}})),document.querySelector(".dashboard").appendChild(o)}function r(e,t,a){const n=document.querySelector(".alerta");n&&n.remove();const o=document.createElement("DIV");o.classList.add("alerta",t),o.textContent=e,a.parentElement.insertBefore(o,a.nextElementSibling),setTimeout(()=>{o.remove()},3e3)}async function c(t){const{estado:a,id:o,nombre:r,proyectoid:c}=t,s=new FormData;s.append("id",o),s.append("nombre",r),s.append("estado",a),s.append("proyectoid",i());try{const t=server+"/api/tareas/actualizar",c=await fetch(t,{method:"POST",body:s}),i=await c.json();if("exito"===i.respuesta.tipo){swal.fire(i.respuesta.mensaje,i.respuesta.mensaje,"success");const t=document.querySelector(".modal");t&&t.remove(),e=e.map(e=>(e.id===o&&(e.estado=a,e.nombre=r),e)),n()}}catch(e){}}function i(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).id}document.querySelectorAll('#filtros input[type="radio"]').forEach(e=>{e.addEventListener("input",a)})}();