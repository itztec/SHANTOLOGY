# MindCare - Psychology Clinic EMR

> **Better Minds · Brighter Futures**  
> Comprehensive Electronic Medical Record (EMR) system tailored specifically for Clinical Psychologists, Behavioral Therapists, and Mental Health Clinics.

---

## 🌟 Overview

**MindCare** is a clinical-grade psychological EMR and practice management system built with modern Laravel, Tailwind CSS, and Alpine.js. Designed around real-world psychiatric workflows, MindCare streamlines patient clinical case histories, Mental Status Examinations (MSE), standardized psychometric assessments, CBT/intervention goal planning, daily consultation scheduling, and printable clinical case summaries.

---

## 🔐 Clinician Portal Login Credentials

To access the clinical portal, use the following default credentials:

- **Clinician ID / Username**: `admin` *(or `admin@mindcare.com`)*
- **Password**: `1234`
- **Clinician Profile**: Dr. Dhritiman Dey (Consultant Clinical Psychologist)

---

## ✨ Clinical Modules & Features

### 1. Clinical Dashboard
- **Real-time Caseload Analytics**: Total registered patients, active in-progress therapy cycles, completed cases, and weekly appointments.
- **Monthly Clinical Progress**: Case completion vs. active caseload metrics.
- **Today's Schedule & Quick Actions**: 1-click patient intake, calendar slot booking, and rapid EMR search (⌘K).

### 2. Patient Directory & Case Profiling
- **Demographics & Pediatric Guardian Tracking**: Sequential ID numbering (`P001`, `P002`, ...), minor caregiver info, and status tags (`In Progress`, `Completed`, `Follow Up`, `Inactive`).
- **Comprehensive Psychiatric Case History**:
  - Presenting Complaints & Onset
  - History of Present Illness (HOPI)
  - Family Psychiatric History
  - Developmental, Educational & Personal History
  - Medical History & Organic Clearances

### 3. Mental Status Examination (MSE)
- Structured clinical evaluation covering 9 standard dimensions:
  1. General Appearance & Co-operation
  2. Psychomotor Behavior & Fidgeting
  3. Speech (Volume, Rate, Coherence)
  4. Mood & Affect (Subjective vs Congruent)
  5. Thought Process (Stream & Form)
  6. Thought Content & Suicidal Risk Screening
  7. Perception & Hallucination Assessment
  8. Cognitive Functions (Orientation & Attention)
  9. Insight & Judgment

### 4. Standardized Psychometric Assessments
- Tracking and scoring of standard clinical batteries:
  - **GAD-7** (Generalized Anxiety Disorder Screener)
  - **Vanderbilt ADHD Parent/Teacher Rating Scale**
  - **CDI-2** (Child Depression Inventory)
- Score interpretation and clinical formulation recommendations.

### 5. Clinical Impression & DSM/ICD Diagnostic Formulation
- Multi-axial clinical formulation with primary presenting concerns tags (`Anxiety`, `Attention Issues`, `Mood Changes`), provisional diagnosis, differential diagnosis, and ICD-11 / DSM-5 diagnostic codes.

### 6. Goal-Oriented Treatment Planning
- Short-term & long-term treatment goals with modalities (CBT, Exposure Therapy, Mindfulness & Somatic Regulation), target milestones, and completion status.

### 7. SOAP Clinical Progress Notes
- Subjective observations, objective behavioral metrics, clinical assessment, treatment plan updates, and between-session homework assignments.

### 8. Calendar & Scheduling
- Clinical consultations timeline with slot duration recommendations (45m psychotherapy, 60m intake, 90m assessment).

### 9. Clinical Reports & PDF Export
- Practice caseload analytics and official A4 Confidential Psychological Evaluation & Case Summary formatted for print and PDF generation.

### 10. Dark Mode & Light Mode
- Class-based theme toggle with zero-FOUC initialization and persistence via `localStorage`.

---

## 🚀 Local Installation & Setup

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/itztec/Mind-Care.git
   cd Mind-Care
   ```

2. **Install PHP and Node Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment & Database**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   touch database/database.sqlite
   php artisan migrate --seed
   ```

4. **Build Frontend Assets**:
   ```bash
   npm run build
   ```

5. **Start Local Development Server**:
   ```bash
   php artisan serve
   ```
   Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your web browser.

---

## 🧪 Testing & Code Quality

- **Run Automated Feature & Unit Tests**:
  ```bash
  php artisan test
  ```
- **Run Laravel Pint Style Formatter**:
  ```bash
  vendor/bin/pint --format agent
  ```

---

## 📄 License

MindCare is open-sourced software licensed under the [MIT license](LICENSE).
