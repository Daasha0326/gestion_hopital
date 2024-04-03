export interface Model<T> {
    data: T
}

export interface Data {
    patients: Patient[]
}

export interface Patient {
    id: number
    nom: string
    prenom: string
    sexe: string
    age: number
    adresse: string
    telephone: number
    numeroDossier: string
}


export interface NewResponse {
    consultations: Consultation[]
}

export interface Consultation {
    id: number
    numero?: number
    numeroRV?: number
    date: string
    etat: string
    patient: Patient
    service: Service
    medecin: Medecin
}

export interface Patient {
    id: number
    nom: string
    prenom: string
    sexe: string
    age: number
    adresse: string
    telephone: number
    numeroDossier: string
}

export interface Service {
    id: number
    nom: string
}

export interface Medecin {
    id: number
    nom: string
    prenom: string
}