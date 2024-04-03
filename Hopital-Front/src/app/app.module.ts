import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
// import { NgSelectModule } from '@ng-select/ng-select';

import { FormsModule } from '@angular/forms';

import { ReactiveFormsModule } from '@angular/forms';
import { NgxPaginationModule } from 'ngx-pagination';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { PatientComponent } from './patient/patient.component';
import { ListePatientComponent } from './patient/liste-patient/liste-patient.component';
import { FormPatientComponent } from './patient/form-patient/form-patient.component';
import { ConsultationComponent } from './patient/consultation/consultation.component';
import { ConnexionComponent } from './connexion/connexion.component';

// import { TailwindModule } from '@ngneat/tailwind';

@NgModule({
  declarations: [
    AppComponent,
    PatientComponent,
    ListePatientComponent,
    FormPatientComponent,
    ConnexionComponent,
    ConsultationComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    ReactiveFormsModule,
    HttpClientModule,
    FormsModule,
    NgxPaginationModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
