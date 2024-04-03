import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { PatientComponent } from './patient/patient.component';
import { ListePatientComponent } from './patient/liste-patient/liste-patient.component';
import { ConsultationComponent } from './patient/consultation/consultation.component';

const routes: Routes = [
  // {
  //   path: 'patient',
  //   component: PatientComponent
  // },
  // {
  //   path: 'listePatient',
  //   component: ListePatientComponent
  // },
  // {
  //   path: 'listeConsultation',
  //   component : ConsultationComponent
  // }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
