<template>
  <br /> <br /> <br /> <br />
  <ejs-schedule
    height='550px'
    width='100%'
    :selectedDate='selectedDate'
    :eventSettings='eventSettings'
    @eventRendered="onEventRendered"
    @eventClick="onEventClick"
  >
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
</template>

<script>
import { ScheduleComponent, Day, Week, WorkWeek, Month, Agenda, DragAndDrop, Resize, HeaderRowsDirective, HeaderRowDirective, ViewsDirective, ViewDirective, ResourcesDirective, ResourceDirective } from "@syncfusion/ej2-vue-schedule";
import axios from 'axios';
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
      selectedDate: new Date(),
      group: { resources: ['Owners'] },
      allowMultiple: true,
      ownerDataSource: [
        { OwnerText: 'Séance', Id: 1, OwnerColor: '#9dcd5a' },
        { OwnerText: 'Examen', Id: 2, OwnerColor: '#DF7588' },
        { OwnerText: 'Michael', Id: 3, OwnerColor: '#7499e1' },
      ],
      eventSettings: {
        dataSource: []
      }
    };
  },
  provide: {
    schedule: [Day, Week, WorkWeek, Month, Agenda, DragAndDrop, Resize]
  },
  methods: {
    createDateFromStrings(dateString, timeString) {
      const [year, month, day] = dateString.split('-').map(Number);
      const [hours, minutes, seconds] = timeString.split(':').map(Number);
      return new Date(year, month - 1, day, hours, minutes, seconds);
    },
    async fetchSeancesAndExamens() {
      try {
        const response = await axios.get('/seance/index');
        const { seances, examens } = response.data;

        // Log fetched data for debugging
        console.log('Fetched seances:', seances);
        console.log('Fetched examens:', examens);

        const updatedExamens = examens.map(element => {
          // Log each element for debugging
          console.log('Processing examen element:', element);
          return {
            Type:"Examen",
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
          // Log each element for debugging
          console.log('Processing seance element:', element);
          return {
            Type:"Seance",
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

        // Log the combined data for debugging
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
    onEventRendered(args) {
      const status = args.data.status;
      console.log('Event rendered:', args.data); // Log event data for debugging
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
        <button id="delete-button" style="background-color: #DF7588; border-color: #DF7588; color: white; padding: 10px; border-radius: 5px; cursor: pointer;">
          Supprimer
        </button>
        <button id="delete-button" style="background-color: #1F4069; border-color: #1F4069; color: white; padding: 10px; border-radius: 5px; cursor: pointer;">
          Modifier
        </button>
        <button id="ok-button" style="background-color: #9dcd5a; border-color: #9dcd5a; color: white; padding: 10px; border-radius: 5px; cursor: pointer;">
          OK
        </button>
      </div>
    `,
    showConfirmButton: false, // Hide default confirm button
    willOpen: () => {
      // Add event listener for the delete button
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
            );
          }
        });
      });
      // Add event listener for the custom OK button
      document.getElementById('ok-button').addEventListener('click', () => {
        Swal.close();
      });
    }
  });
},
deleteEvent(eventData) {
  // Find the index of the event in the dataSource
  const index = this.eventSettings.dataSource.findIndex(event => event.Id === eventData.Id);
  if (index !== -1) {
    // Remove the event from the dataSource
    this.eventSettings.dataSource.splice(index, 1);
    // Update the schedule
    this.$refs.schedule.refreshEvents();
  }
}

  },
  async mounted() {
    let isSuperAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "superAdmin";
    if (isSuperAdmin) {
      window.location.href = '/dashbord_Super_Admin';
    } else {
      await this.fetchSeancesAndExamens();
    }
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
