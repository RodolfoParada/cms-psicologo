(function () {
    'use strict';

    const { Schedule } = calendarjs;

    let schedule = null;
    let editando = false;
    let eventoActual = null;

    const modal = document.getElementById('modalCita');
    const modalTitulo = document.getElementById('modalTitulo');
    const citaId = document.getElementById('citaId');
    const pacienteNombre = document.getElementById('pacienteNombre');
    const pacienteTelefono = document.getElementById('pacienteTelefono');
    const pacienteEmail = document.getElementById('pacienteEmail');
    const citaFecha = document.getElementById('citaFecha');
    const citaTipo = document.getElementById('citaTipo');
    const citaHoraInicio = document.getElementById('citaHoraInicio');
    const citaHoraFin = document.getElementById('citaHoraFin');
    const citaEstado = document.getElementById('citaEstado');
    const estadoGroup = document.getElementById('estadoGroup');
    const citaMotivo = document.getElementById('citaMotivo');
    const modalError = document.getElementById('modalError');
    const btnEliminar = document.getElementById('btnEliminar');
    const modalGuardar = document.getElementById('modalGuardar');
    const btnGuardarTexto = document.getElementById('btnGuardarTexto');
    const modalCancelar = document.getElementById('modalCancelar');
    const modalClose = document.getElementById('modalCitaClose');

    function formatTime(h, m) {
        return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
    }

    function getDefaultEnd(start) {
        const partes = start.split(':');
        const h = parseInt(partes[0], 10);
        const m = parseInt(partes[1], 10);
        const duracion = 50;
        const totalMinutos = h * 60 + m + duracion;
        return formatTime(Math.floor(totalMinutos / 60), totalMinutos % 60);
    }

    function buildEvents(citas) {
        return citas.map(function (c) {
            return {
                guid: String(c.id),
                title: c.title,
                start: c.start,
                end: c.end,
                date: c.date,
                color: c.color,
                estado: c.estado,
                tipo: c.tipo,
                motivo: c.motivo,
                telefono: c.telefono,
                email: c.email,
                description: c.motivo || '',
            };
        });
    }

    function getValidRange() {
        return ['08:00', '20:00'];
    }

    function getReadOnlyRange() {
        if (modoVacaciones) {
            return [['00:00', '23:59']];
        }
        const rangos = [];
        if (vacacionesData && vacacionesData.length > 0) {
            rangos.push(['00:00', '23:59']);
        }
        return rangos;
    }

    function openModal(eventData, editandoMode) {
        editando = editandoMode;
        eventoActual = eventData;

        if (editandoMode) {
            modalTitulo.textContent = 'Editar cita';
            btnGuardarTexto.textContent = 'Actualizar cita';
            citaId.value = eventData.guid || eventData.id || '';
            pacienteNombre.value = eventData.title ? eventData.title.replace(/ \(Online\)| \(Presencial\)/g, '') : '';
            pacienteTelefono.value = eventData.telefono || '';
            pacienteEmail.value = eventData.email || '';
            citaFecha.value = eventData.date || '';
            citaTipo.value = eventData.tipo || 'online';
            citaHoraInicio.value = eventData.start || '';
            citaHoraFin.value = eventData.end || '';
            citaEstado.value = eventData.estado || 'pendiente';
            estadoGroup.style.display = 'block';
            citaMotivo.value = eventData.motivo || eventData.description || '';
            btnEliminar.style.display = 'inline-block';
        } else {
            modalTitulo.textContent = 'Nueva cita';
            btnGuardarTexto.textContent = 'Guardar cita';
            citaId.value = '';
            pacienteNombre.value = '';
            pacienteTelefono.value = '';
            pacienteEmail.value = '';
            citaFecha.value = eventData.date || '';
            citaTipo.value = eventData.tipo || 'online';
            citaHoraInicio.value = eventData.start || '';
            citaHoraFin.value = eventData.end ? eventData.end : getDefaultEnd(eventData.start || '09:00');
            citaEstado.value = 'pendiente';
            estadoGroup.style.display = 'none';
            citaMotivo.value = '';
            btnEliminar.style.display = 'none';
        }

        modalError.style.display = 'none';
        modal.classList.add('visible');
    }

    function cerrarModal() {
        modal.classList.remove('visible');
        editando = false;
        eventoActual = null;
    }

    function mostrarError(msg) {
        modalError.textContent = msg;
        modalError.style.display = 'block';
    }

    function guardarCita() {
        const data = {
            paciente_nombre: pacienteNombre.value.trim(),
            paciente_telefono: pacienteTelefono.value.trim(),
            paciente_email: pacienteEmail.value.trim(),
            fecha: citaFecha.value,
            hora_inicio: citaHoraInicio.value,
            hora_fin: citaHoraFin.value,
            tipo: citaTipo.value,
            motivo: citaMotivo.value.trim(),
        };

        if (!data.paciente_nombre) {
            mostrarError('El nombre del paciente es obligatorio.');
            return;
        }
        if (!data.paciente_telefono) {
            mostrarError('El teléfono del paciente es obligatorio.');
            return;
        }
        if (!data.fecha) {
            mostrarError('La fecha es obligatoria.');
            return;
        }
        if (!data.hora_inicio) {
            mostrarError('La hora de inicio es obligatoria.');
            return;
        }
        if (!data.hora_fin) {
            mostrarError('La hora de fin es obligatoria.');
            return;
        }

        modalGuardar.disabled = true;
        modalGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        let url, method;

        if (editando) {
            url = '/panel-psicologa/calendario/citas/' + citaId.value;
            method = 'PUT';
            data.estado = citaEstado.value;
        } else {
            url = '/panel-psicologa/calendario/citas';
            method = 'POST';
        }

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify(data),
        })
        .then(function (res) {
            if (!res.ok) {
                return res.json().then(function (err) { throw err; });
            }
            return res.json();
        })
        .then(function (result) {
            if (editando) {
                const evts = schedule.getData();
                for (let i = 0; i < evts.length; i++) {
                    if (evts[i].guid === result.cita.id || evts[i].guid === String(result.cita.id)) {
                        schedule.updateEvent(evts[i], {
                            title: result.cita.title,
                            start: result.cita.start,
                            end: result.cita.end,
                            date: result.cita.date,
                            color: result.cita.color,
                            estado: result.cita.estado,
                            tipo: result.cita.tipo,
                            motivo: result.cita.motivo,
                            telefono: result.cita.telefono,
                            email: result.cita.email,
                        });
                        break;
                    }
                }
            } else {
                schedule.addEvents({
                    guid: String(result.cita.id),
                    title: result.cita.title,
                    date: result.cita.date,
                    start: result.cita.start,
                    end: result.cita.end,
                    color: result.cita.color,
                    estado: result.cita.estado,
                    tipo: result.cita.tipo,
                    motivo: result.cita.motivo,
                    telefono: result.cita.telefono,
                    email: result.cita.email,
                });
            }
            cerrarModal();
            mostrarToast(result.message, 'success');
        })
        .catch(function (err) {
            if (err.errors) {
                const msgs = Object.values(err.errors).flat();
                mostrarError(msgs.join('\n'));
            } else {
                mostrarError('Error al guardar la cita. Intenta de nuevo.');
            }
        })
        .finally(function () {
            modalGuardar.disabled = false;
            modalGuardar.innerHTML = '<i class="fas fa-save"></i> ' + (editando ? 'Actualizar cita' : 'Guardar cita');
        });
    }

    function eliminarCita() {
        if (!confirm('¿Eliminar esta cita? Esta acción no se puede deshacer.')) return;

        fetch('/panel-psicologa/calendario/citas/' + citaId.value, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Error al eliminar');
            return res.json();
        })
        .then(function () {
            const evts = schedule.getData();
            for (let i = 0; i < evts.length; i++) {
                if (evts[i].guid === citaId.value) {
                    schedule.deleteEvents(evts[i]);
                    break;
                }
            }
            cerrarModal();
            mostrarToast('Cita eliminada correctamente.', 'success');
        })
        .catch(function () {
            mostrarError('Error al eliminar la cita.');
        });
    }

    function mostrarToast(msg, tipo) {
        const contenedor = document.getElementById('toast-container');
        if (!contenedor) {
            const div = document.createElement('div');
            div.id = 'toast-container';
            div.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
            document.body.appendChild(div);
        }

        const toast = document.createElement('div');
        toast.textContent = msg;
        toast.style.cssText = 'padding:12px 20px;border-radius:8px;font-size:0.9rem;font-weight:500;color:#fff;background:' + (tipo === 'success' ? '#10b981' : '#ef4444') + ';box-shadow:0 4px 12px rgba(0,0,0,0.15);animation:slideIn 0.3s ease;';
        document.getElementById('toast-container').appendChild(toast);

        setTimeout(function () {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(function () { toast.remove(); }, 300);
        }, 3000);
    }

    function init() {
        const container = document.getElementById('schedule-container');
        if (!container) return;

        const eventos = buildEvents(citasData || []);

        function handleCreate(self, events) {
            const evt = Array.isArray(events) ? events[0] : events;
            const tipoDetectado = evt.location === 'Presencial' ? 'presencial' : 'online';
            openModal({
                date: evt.date,
                start: evt.start,
                end: evt.end,
                tipo: tipoDetectado,
            }, false);
            schedule.deleteEvents(evt);
        }

        function handleEdition(self, event) {
            openModal(event, true);
        }

        function handleChange(self, newValue, oldValue) {
            if (!newValue.guid) return;

            const payload = {
                paciente_nombre: newValue.title ? newValue.title.replace(/ \(Online\)| \(Presencial\)/g, '').trim() : '',
                paciente_telefono: newValue.telefono || '',
                paciente_email: newValue.email || '',
                fecha: newValue.date || '',
                hora_inicio: newValue.start || '',
                hora_fin: newValue.end || '',
                tipo: newValue.tipo || 'online',
                estado: newValue.estado || 'pendiente',
                motivo: newValue.motivo || '',
            };

            fetch('/panel-psicologa/calendario/citas/' + newValue.guid, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(payload),
            }).catch(function () {});
        }

        schedule = Schedule(container, {
            type: 'week',
            value: new Date().toISOString().split('T')[0],
            data: eventos,
            grid: 15,
            validRange: getValidRange(),
            overlap: false,
            oncreate: handleCreate,
            onedition: handleEdition,
            onchangeevent: handleChange,
        });
    }

    document.addEventListener('DOMContentLoaded', init);

    modalGuardar.addEventListener('click', guardarCita);
    modalCancelar.addEventListener('click', cerrarModal);
    modalClose.addEventListener('click', cerrarModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) cerrarModal();
    });

    btnEliminar.addEventListener('click', eliminarCita);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') cerrarModal();
    });

    if (!document.getElementById('toast-style')) {
        const style = document.createElement('style');
        style.id = 'toast-style';
        style.textContent = '@keyframes slideIn {from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
        document.head.appendChild(style);
    }


})();
