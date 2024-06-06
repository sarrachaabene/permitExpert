<template>
  <br><br><br><br>
  <div id='app'>
    <div id='container'>
      <ejs-schedule 
        :height='height'
        :width='width'
        :views='views'
        :selectedDate='selectedDate'
        :eventSettings='eventSettings'
        :editorTemplate="'editorTemplate'"
        :popupOpen='onPopupOpen'
        :popupClose='onPopupClose'
        @eventClick="onEventClick"
        @eventRendered="onEventRendered">
        <template v-slot:editorTemplate>
          <div class="modal fade show" style="display: block;">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title mx-auto" style="font-weight: bold; margin-top: 30px">Ajouter événement</h5>
                  <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close" @click="onPopupClose">
                    <span class="fas fa-times fs--1"></span>
                  </button>
                </div>
                <br />
                <div class="modal-body">
                  <form @submit.prevent="submitForm">
                    <div class="mb-3">
                      <h6><strong>Type d'évènement</strong></h6>
                      <select v-model="newEvent.type" class="form-select">
                        <option value="seance">Séance</option>
                        <option value="examen">Examen</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <h6><strong>Type</strong></h6>
                      <select v-model="newEvent.subject" class="form-select">
                        <option value="code">Code</option>
                        <option value="circuit">Circuit</option>
                        <option value="parc">Parc</option>
                      </select>
                    </div>
                    <div class="mb-3" v-if="newEvent.type === 'examen'">
                      <h6><strong>Date</strong></h6>
                      <input v-model="newEvent.dateE" class="form-control" type="date" placeholder="Date" />
                    </div>
                    <div class="mb-3" v-if="newEvent.type === 'seance'">
                      <h6><strong>Date</strong></h6>
                      <input v-model="newEvent.dateS" class="form-control" type="date" placeholder="Date" />
                    </div>
                    <div class="mb-3">
                      <h6><strong>Heure début</strong></h6>
                      <input v-model="newEvent.heureD" class="form-control" type="time" placeholder="Heure début" />
                    </div>
                    <div class="mb-3">
                      <h6><strong>Heure Fin</strong></h6>
                      <input v-model="newEvent.heureF" class="form-control" type="time" placeholder="Heure Fin" />
                    </div>
                    <div class="mb-3">
                      <h6><strong>Nom du candidat</strong></h6>
                      <select v-model="newEvent.candidat_nom" class="form-control">
                        <option v-for="candidat in candidats" :key="candidat.id" :value="candidat.user_name">
                          {{ candidat.user_name }}
                        </option>
                      </select>
                    </div>
                    <div class="mb-3" v-if="newEvent.type === 'seance'">
                      <h6><strong>Nom du moniteur</strong></h6>
                      <select v-model="newEvent.moniteur_nom" class="form-control">
                        <option v-for="moniteur in moniteurs" :key="moniteur.id" :value="moniteur.user_name">
                          {{ moniteur.user_name }}
                        </option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <h6><strong>Immatricule du véhicule</strong></h6>
                      <select v-model="newEvent.vehicule_immatriculation" class="form-control">
                        <option v-for="vehicule in vehicules" :key="vehicule.id" :value="vehicule.immatricule">
                          {{ vehicule.immatricule }}
                        </option>
                      </select>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                        Ajouter
                      </button>
                      <button type="button" class="btn btn-secondary" style="background-color: #fa7f35; border-color: #fa7f35" @click="onPopupClose">
                        Annuler
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </template>
        <e-views>
          <e-view option='Day'></e-view>
          <e-view option='Week' startHour='07:00' endHour='15:00'></e-view>
          <e-view option='WorkWeek' startHour='10:00' endHour='18:00'></e-view>
          <e-view option='Month' showWeekend=false></e-view>
          <e-view option='Agenda'></e-view>
        </e-views>
        <e-resources>
          <e-resource
            field="OwnerId"
            title="Owner"
            name="Owners"
            :dataSource="ownerDataSource"
            textField="OwnerText"
            idField="Id"
            colorField="OwnerColor"
          >
          </e-resource>
        </e-resources>
        <e-header-rows>
          <e-header-row option="Date"></e-header-row>
          <e-header-row option="Hour"></e-header-row>
        </e-header-rows>
      </ejs-schedule>
    </div>
  </div>
</template>


<script>
import { DropDownList } from '@syncfusion/ej2-dropdowns';
import { DateTimePicker } from '@syncfusion/ej2-calendars';
import { ScheduleComponent, Day, Week, WorkWeek, Month, Agenda, DragAndDrop, Resize, HeaderRowsDirective, HeaderRowDirective, ViewsDirective, ViewDirective, ResourcesDirective, ResourceDirective } from "@syncfusion/ej2-vue-schedule";
import axios from 'axios';
const VEHICULE_API_BASE_URL = "http://localhost:8000/api/vehicule";
const USER_API_BASE_URL = "http://localhost:8000/api/user";
import '../../../../node_modules/@syncfusion/ej2-base/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-buttons/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-calendars/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-dropdowns/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-inputs/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-navigations/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-popups/styles/material.css';
import '../../../../node_modules/@syncfusion/ej2-vue-schedule/styles/material.css';

export default {
  name: "App",
  data() {
    return {
      users: [],
      vehicules: [],
      candidats:[],
      moniteurs:[],
      selectedDate: new Date(),
      group: { resources: ['Owners'] },
      allowMultiple: true,
      height: '700px',
      width: '100%',
      ownerDataSource: [
        { OwnerText: 'Séance', Id: 1, OwnerColor: '#9dcd5a' },
        { OwnerText: 'Examen', Id: 2, OwnerColor: '#DF7588' },
        { OwnerText: 'Michael', Id: 3, OwnerColor: '#7499e1' },
      ],
      eventSettings: {
        dataSource: []
      },
      showQuickInfo: false,
      selectedEvent: {
      Type: '',
      Subject: '',
      StartTime: '',
      EndTime: '',
      user_name: '',
      Moniteur_name: '',
      immatricule: ''
    },
    newEvent: {
        type: 'seance',
        subject: '',
        dateE: '',
        dateS:'',
        heureD: '',
        heureF: '',
        candidat_nom: '',
        moniteur_nom: '',
        vehicule_immatriculation: ''
      }

    };
  },
  provide: {
    schedule: [Day, Week, WorkWeek, Month, Agenda, DragAndDrop, Resize]
  },
  methods: {
    hideElements(selector) {
      const elements = document.querySelectorAll(selector);
      elements.forEach(element => {
        element.style.display = 'none';
      });
    },
    async fetchVehicule() {
  try {
    const response = await axios.get(`${VEHICULE_API_BASE_URL}/index`);
    this.vehicules = response.data
    console.log("Fetched vehicules:", this.vehicules);
  } catch (error) {
    console.error("Error fetching vehicules:", error);
  }
},
async fetchCandidat() {
  try {
    const response = await axios.get(`${USER_API_BASE_URL}/index`);
    this.candidats = response.data.filter(user => ['candidat'].includes(user.role));
    console.log("Fetched candidats:", this.candidats);
  } catch (error) {
    console.error("Error fetching candidats:", error);
  }
},
async fetchMoniteur() {
  try {
    const response = await axios.get(`${USER_API_BASE_URL}/index`);
    this.moniteurs = response.data.filter(user => ['moniteur'].includes(user.role));
    console.log("Fetched moniteurs:", this.moniteurs);
  } catch (error) {
    console.error("Error fetching moniteurs:", error);
  }
},
    onPopupClose: function () {
  const modalElements = document.querySelectorAll('.modal.fade.show');
  modalElements.forEach(modalElement => {
    modalElement.style.display = 'none';
  });
  this.newEvent = {
        type: 'seance',
        subject: '',
        dateE: '',
        heureD: '',
        heureF: '',
        candidat_nom: '',
        moniteur_nom: '',
        dateS:'',
        vehicule_immatriculation: ''
      };
    //  this.$refs.schedule.closeQuickInfoPopup();
},


    deleteEvent(eventData) {
  const index = this.eventSettings.dataSource.findIndex(event => event.Id === eventData.Id);
  if (index !== -1) {
    this.eventSettings.dataSource.splice(index, 1);
    if (this.$refs.schedule && typeof this.$refs.schedule.refreshEvents === 'function') {
      this.$refs.schedule.refreshEvents();
    } else {
      console.error('La référence au composant de planning ou la méthode refreshEvents() n\'est pas définie.');
    }
  }
},
async submitForm() {
      const { type, subject, dateE,dateS, heureD, heureF, candidat_nom, moniteur_nom, vehicule_immatriculation } = this.newEvent;
      const apiUrl = type === 'seance' ? '/seance/store' : '/Examen/store';
      const payload = {
        type: subject,
        ...(type=='examen' && {dateE}),
        ...(type=='seance' && {dateS}),
        heureD,
        heureF,
        candidat_nom,
        vehicule_immatriculation,
        ...(type === 'seance' && { moniteur_nom })
      };

      try {
        await axios.post(apiUrl, payload);
        this.fetchSeancesAndExamens();
        this.onPopupClose();
      } catch (error) {
        if (error.response && error.response.data) {
          console.error('API Error:', error.response.data);
        } else {
          console.error('Unexpected Error:', error);
        }
      }
    },
    createDateFromStrings(dateString, timeString) {
      const [year, month, day] = dateString.split('-').map(Number);
      const [hours, minutes, seconds] = timeString.split(':').map(Number);
      return new Date(year, month - 1, day, hours, minutes, seconds);
    },
    async fetchSeancesAndExamens() {
      try {
        const response = await axios.get('/seance/index');
        const { seances, examens } = response.data;

        console.log('Fetched seances:', seances);
        console.log('Fetched examens:', examens);

        const updatedExamens = examens.map(element => {
          console.log('Processing examen element:', element);
          return {
            Type:"examen",
            Id: element.id,
            user_name:element.user_name,
            immatricule:element.immatricule,
            Subject: element.type,
            EventType: element.status,
            StartTime: this.createDateFromStrings(element.dateE, element.heureD),
            EndTime: this.createDateFromStrings(element.dateE, element.heureF),
            status: element.status,
            OwnerId: 2
          };
        });

        const updatedSeance = seances.map(element => {
          console.log('Processing seance element:', element);
          return {
            Type:"seance",
            Id: element.id,
            Subject: element.type,
            immatricule:element.immatricule,
            user_name:element.user_name,
            Moniteur_name:element.Moniteur_name,
            EventType: element.status,
            StartTime: this.createDateFromStrings(element.dateS, element.heureD),
            EndTime: this.createDateFromStrings(element.dateS, element.heureF),
            status: element.status,
            OwnerId: 1
          };
        });

        const updatedEvents = [...updatedSeance, ...updatedExamens];
        console.log('Combined events:', updatedEvents);

        this.eventSettings = {
          ...this.eventSettings,
          dataSource: updatedEvents
        };
      } catch (error) {
        console.error('Error fetching seances and examens:', error);
      }
    },

    async updateEvent(eventData) {
  try {
    if (eventData.Type === 'seance') {
      await axios.put(`http://localhost:8000/api/seance/update/${eventData.Id}`, {
        type: eventData.Subject,
        date: eventData.StartTime.toISOString().split('T')[0],
        startTime: eventData.StartTime.toTimeString().split(' ')[0],
        endTime: eventData.EndTime.toTimeString().split(' ')[0],
        user_id: eventData.user_id,
        moniteur_id: eventData.moniteur_id,
        vehicule_id: eventData.vehicule_id
      });
      console.log("Séance mise à jour avec succès");
    } else if (eventData.Type === 'examen') {
      await axios.put(`http://localhost:8000/api/Examen/update/${eventData.Id}`, {
        type: eventData.Subject,
        date: eventData.StartTime.toISOString().split('T')[0],
        startTime: eventData.StartTime.toTimeString().split(' ')[0],
        endTime: eventData.EndTime.toTimeString().split(' ')[0],
        user_id: eventData.user_id,
        moniteur_id: eventData.moniteur_id,
        vehicule_id: eventData.vehicule_id
      });
      console.log("Examen mis à jour avec succès");
    }
  } catch (error) {
    console.error("Erreur lors de la mise à jour de l'événement:", error);
  }
},
    onEventRendered(args) {
      const status = args.data.status;
      console.log('Event rendered:', args.data);
      const statusElement = document.createElement('div');
      statusElement.className = 'event-status';
      statusElement.innerText = `Status: ${status}`;
      args.element.appendChild(statusElement);
    },
    onEventClick(args) {
  const eventData = args.event;
  Swal.fire({
    title: "Détails sur l'événement",
    html: `
      Type d'évènement: ${eventData.Type}<br>
      Type : ${eventData.Subject}<br>
      Status: ${eventData.status}<br>
      Heure début : ${new Date(eventData.StartTime).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}<br>
      Heure fin: ${new Date(eventData.EndTime).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}<br>
      Nom du candidat: ${eventData.user_name}<br>
      Nom du moniteur: ${eventData.Moniteur_name}<br>
      Véhicule: ${eventData.immatricule}<br>
      <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <button id="delete-button" style="background-color: #fa7f35; border-color: #fa7f35; color: white; padding: 10px; border-radius: 5px; cursor: pointer;">
          Supprimer
        </button>
        <button id="modify-button" style="background-color: #1F4069; border-color: #1F4069; color: white; padding: 10px; border-radius: 5px; cursor: pointer;">
          Modifier
        </button>
        <button id="ok-button" style="background-color: #9dcd5a; border-color: #9dcd5a; color: white; padding: 10px; border-radius: 5px; cursor: pointer;">
          OK
        </button>
      </div>
    `,
    showConfirmButton: false, 
    willOpen: () => {
      document.getElementById('delete-button').addEventListener('click', () => {
        Swal.fire({
          title: 'Êtes-vous sûr?',
          text: "Vous ne pourrez pas revenir en arrière!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#fa7f35',
          cancelButtonColor: '#9dcd5a',
          confirmButtonText: 'Oui, supprimer!'
        }).then((result) => {
          if (result.isConfirmed) {
            this.deleteEvent(eventData);
            Swal.fire(
          'Supprimé!',
          'Votre événement a été supprimé.',
          'success'
        ).then(() => {
          window.location.reload();
        });
          }
        });
      });
      document.getElementById('ok-button').addEventListener('click', () => {
        Swal.close();
      });

      
      document.getElementById('modify-button').addEventListener('click', () => {

  Swal.fire({
    html:
    `
    <form>
      <div class="mb-3">
        <h5 class="modal-title mx-auto" id="exampleModalLabel" style="font-weight: bold; margin-top: 30px">
          Modifier l'événement
        </h5>
        <br>
        <h6 style="text-align: left;"><strong>Type</strong></h6>
        <select class="form-select" >
          <option value="code">Code</option>
          <option value="circuit">Circuit</option>
          <option value="parc">Parc</option>
        </select>
      </div>
      <div class="mb-3">
        <h6 style="text-align: left;"><strong>Type d'évènement</strong></h6>
                      <select  class="form-select">
                        <option value="seance">Séance</option>
                        <option value="examen">Examen</option>
                      </select>
                    </div>
      <div class="mb-3">
        <h6 style="text-align: left;"><strong>Date</strong></h6>
        <input name="Date" class="form-control" type="date" placeholder="Date" />
      </div>
      <div class="mb-3" >
        <h6 style="text-align: left;"><strong>Heure début</strong></h6>
        <input name="Heure début" class="form-control" type="time" placeholder="Heure début" />
      </div>
      <div class="mb-3" >
        <h6 style="text-align: left;"><strong>Heure Fin</strong></h6>
        <input name="Heure début" class="form-control" type="time" placeholder="Heure début" />
      </div>
      <div class="mb-3">
        <h6 style="text-align: left;"><strong>Nom du candidat</strong></h6>
        <select class="form-control"  placeholder="Nom du candidat">
          ${this.candidats.map(user => `<option value="${user.user_name}">${user.user_name}</option>`).join('')}
        </select>
      </div>
      <div class="mb-3">
        <h6 style="text-align: left;"><strong>Nom du moniteur</strong></h6>
        <select class="form-control"  placeholder="Nom du moniteur">
          ${this.moniteurs.map(user => `<option value="${user.user_name}">${user.user_name}</option>`).join('')}
        </select>
      </div>
      <div class="mb-3">
        <h6 style="text-align: left;"><strong>Immatricule du véhicule</strong></h6>
        <select class="form-control" placeholder="Immatricule">
          ${this.vehicules.map(vehicule => `<option value="${vehicule.immatricule}">${vehicule.immatricule}</option>`).join('')}
        </select>
      </div>
    <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                Modifier
              </button>    </form>
    `
  });
});
 this.updateEvent(eventData);
//  Swal.fire("L'événement a été mis à jour avec succès!"); 
    }
    
  });
},
deleteEvent(eventData) {
  const eventId = eventData.Id;
  const eventType = eventData.Type;
  axios.delete(`/seance/delete/${eventId}/${eventType}`)
    .then(response => {
      if (response.status === 200) {
        const index = this.eventSettings.dataSource.findIndex(event => event.id === eventId);
        if (index !== -1) {
          this.eventSettings.dataSource.splice(index, 1);
          this.$refs.schedule.refreshEvents();
        }
/*         Swal.fire(
          'Supprimé!',
          'Votre événement a été supprimé.',
          'success'
        ); */
        
      } else {
        Swal.fire(
          'Erreur!',
          'La suppression de l\'événement a échoué.',
          'error'
        );
      }
    })
    .catch(error => {
      console.error('Erreur lors de la suppression de l\'événement:', error);
      Swal.fire(
        'Erreur!',
        'Une erreur est survenue lors de la suppression de l\'événement.',
        'error'
      );
    });
},

  },
  async mounted() {
    let isSuperAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "superAdmin";
    if (isSuperAdmin) {
      window.location.href = '/dashbord_Super_Admin';
    } else {
      await this.fetchSeancesAndExamens();
    }
    await this.fetchVehicule(); 
  await this.fetchCandidat();     
  await this.fetchMoniteur();   
  if (this.vehicules.length === 0 || this.users.length === 0) {
    console.error('Vehicules or users array is empty');
    return;
  };
  },
  components: {
    'ejs-schedule': ScheduleComponent,
    'e-views': ViewsDirective,
    'e-view': ViewDirective,
    'e-resources': ResourcesDirective,
    'e-resource': ResourceDirective,
    'e-header-rows': HeaderRowsDirective,
    'e-header-row': HeaderRowDirective
  }
};
</script>
<style>
.custom-event-editor .e-textlabel {
  padding-right: 15px;
  text-align: right;
}

.custom-event-editor td {
  padding: 7px;
  padding-right: 16px;
}
</style>