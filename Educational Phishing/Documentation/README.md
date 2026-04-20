# PhishGuard: A Controlled Phishing Simulation Platform for Security Awareness Education

**Project Type:** Graduation Project
**Field:** Information Security / Web Application Security
**Academic Level:** Undergraduate

---

## Abstract

Phishing remains one of the most prevalent and effective vectors of cybercrime, accounting for a significant proportion of credential theft and data breaches globally. Despite widespread awareness campaigns, users continue to fall victim to these attacks largely because conceptual understanding of phishing does not translate into reliable behavioral recognition of it in practice.

This project presents PhishGuard, a controlled phishing simulation platform built for use in structured educational settings. The system replicates the technical components of a phishing attack — a deceptive login interface, a credential-handling backend, and an audit logging mechanism — within a fully transparent, consent-based environment. Its purpose is twofold: to expose participants to the experience of a phishing attempt under controlled conditions, and to provide instructors with a technically detailed codebase that demonstrates both the attack surface and the defensive controls used to address it.

The platform is implemented in PHP with a MySQL backend and adheres throughout to secure coding practices including prepared statements, BCRYPT password hashing, CSRF token validation, input sanitization, and structured audit logging. The codebase is additionally organized according to clean code principles, making it suitable as a secondary reference for secure PHP development.

---

## Problem Statement

The majority of phishing education delivered in academic and organizational settings relies on passive instruction: lectures, warnings, and written guidelines. Research in security awareness training has consistently shown that passive exposure produces limited behavioral change. Users who have been told what phishing looks like do not reliably identify it when encountered, particularly when the phishing interface is visually convincing.

There is a recognized need for experiential learning tools that allow users to encounter simulated phishing in a safe environment, process the experience with guided reflection, and retain the perceptual skills required to detect similar attacks in real contexts. This project addresses that gap by providing a technically authentic simulation that can be deployed and controlled by an instructor in a classroom or lab setting.

---

## Project Objectives

1. To construct a technically accurate simulation of a phishing attack that replicates the visual and functional characteristics of real phishing pages.
2. To implement a secure backend that demonstrates industry-standard defensive controls against the vulnerabilities exploited by phishing attacks.
3. To provide structured educational content that guides participants from the simulated attack experience through to an understanding of the underlying mechanisms and available defenses.
4. To produce a clean, well-documented codebase that can serve as a reference implementation of secure PHP development practices.
5. To establish an ethical and legal framework for the deployment of the simulation, including informed consent, data isolation, and transparent disclosure.

---

## Methodology

The project follows a design-and-build methodology structured around three parallel concerns: attack replication, defensive implementation, and pedagogical design.

**Attack replication.** The frontend component reproduces the visual and behavioral characteristics of a phishing login page, including form handling, redirect behavior, and the absence of cues that would indicate illegitimacy to an inattentive user. This component is intentionally realistic so that the simulation provides meaningful perceptual training.

**Defensive implementation.** The backend is implemented in two versions. The first (`login_secure.php`) is a functional implementation with all security controls active. The second (`login_secure_clean.php`) is a refactored version of the same logic organized according to clean code principles, with responsibilities separated into dedicated utility classes. Both versions are intended for code-level analysis during instruction.

**Pedagogical design.** The user flow is structured to produce a specific learning arc: the participant encounters the simulation without prior knowledge of its content, submits credentials, and is then immediately informed of what occurred and why. The debriefing content and the accompanying protection guide are designed to consolidate the experience into transferable knowledge.

---

## System Architecture

```
phishing-educational/
├── config.php                        # Centralized environment configuration
├── .gitignore
├── README.md
├── SETUP.md
│
├── frontend/
│   ├── warning.html                  # Informed consent and legal disclosure
│   ├── index_educational.html        # Simulated phishing login interface
│   ├── thank_you_educational.html    # Post-submission disclosure and debrief
│   └── protection_guide.html         # Defensive techniques reference
│
├── backend/
│   ├── login_secure.php              # Primary form handler (security controls active)
│   └── login_secure_clean.php        # Refactored handler (clean code architecture)
│
├── includes/
│   ├── Logger.php                    # Structured event and error logging
│   ├── Database.php                  # PDO database abstraction layer
│   ├── Validator.php                 # Input validation and sanitization
│   └── Security.php                  # Cryptographic and session security utilities
│
├── database/
│   └── phishing_educational.sql      # Schema: users, submissions, audit_logs
│
├── logs/
└── temp/
```

---

## Component Descriptions

### Frontend Layer

`warning.html` presents the informed consent screen. Before any simulation content is displayed, the participant is informed of the educational nature of the exercise, the data handling policy, and the legal boundaries of the project. Participation requires explicit acknowledgment.

`index_educational.html` is the simulated phishing interface. It is designed to present as a plausible credential entry form in order to provide a realistic simulation experience. The page contains no hidden functionality beyond what is explicitly described in the project documentation.

`thank_you_educational.html` is displayed immediately after form submission. It discloses the simulation, explains what data was submitted and how it was handled, and presents the first layer of educational content covering how the attack works and what indicators were present.

`protection_guide.html` is a standalone reference document covering phishing detection heuristics, a comparison of vulnerable and secure code patterns, a security self-assessment checklist, and guidance on reporting suspected phishing.

### Backend Layer

`login_secure.php` is the primary form handler. It receives submitted credentials, validates and sanitizes all input, verifies the CSRF token, logs the interaction to the audit table, and produces a response. All database interactions use prepared statements. Passwords are hashed using BCRYPT before storage.

`login_secure_clean.php` is a refactored version of the same handler, reorganized to comply with clean code principles. Each concern — database access, input validation, security operations, and logging — is handled by a dedicated class in the `includes/` directory. This version is intended for code-level review to illustrate how security controls integrate into well-structured application architecture.

`config.php` centralizes all environment-specific configuration. No credentials, paths, or environment flags appear elsewhere in the codebase.

### Utility Classes

`Database.php` provides a PDO-based abstraction layer. All queries are executed through prepared statements. The class manages connection lifecycle and exposes a consistent interface used by both backend handlers.

`Validator.php` implements input validation and output sanitization routines. All user-supplied input passes through this class before use in any database query or HTTP response.

`Security.php` provides CSRF token generation and verification, BCRYPT hashing wrappers, and security header injection. It is the single point of control for all cryptographic and session-related operations.

`Logger.php` handles structured logging to the `logs/` directory and to the `audit_logs` database table. All form submission events, validation failures, and authentication outcomes are logged with timestamps and session identifiers.

---

## Security Controls

The table below summarizes the security controls implemented in the project and identifies the component responsible for each.

| Control | Threat Addressed | Implementing Component | Status |
|---|---|---|---|
| Prepared statements | SQL injection | Database.php | Implemented |
| BCRYPT password hashing | Credential exposure | Security.php | Implemented |
| CSRF token validation | Cross-site request forgery | Security.php | Implemented |
| Input validation | Malformed or malicious input | Validator.php | Implemented |
| Output sanitization | Cross-site scripting (XSS) | Validator.php | Implemented |
| Secure HTTP headers | Clickjacking, MIME sniffing, XSS | Security.php | Implemented |
| Rate limiting | Brute force attacks | login_secure_clean.php | Implemented |
| Audit logging | Traceability and accountability | Logger.php | Implemented |
| Two-factor authentication | Account takeover | — | Not implemented |
| OAuth 2.0 integration | Credential delegation | — | Not implemented |
| Server-side session encryption | Session hijacking | — | Not implemented |

Controls marked as not implemented represent identified areas for future extension and are discussed in the project's conclusions chapter.

---

## Ethical Considerations

The deployment of phishing simulation tools carries inherent ethical risks that this project addresses through the following design decisions.

**Informed consent.** The simulation is not deployed without the participant's prior knowledge. The warning page makes the educational nature of the exercise explicit before any simulation content is displayed. Participants who do not consent are not shown the simulated interface.

**Data isolation.** All submitted data is stored in a local database that is not connected to any external service. No credentials leave the machine on which the application is deployed. The database is intended to be deleted or reset after each session.

**Transparency.** The full source code, database schema, and documentation are available for review. There are no undisclosed components or behaviors.

**Scope limitation.** The application is configured for local deployment only. It has no mechanism for public-facing deployment and is not designed to operate outside a controlled, single-machine environment.

---

## Limitations and Future Work

The current implementation addresses the core simulation and a representative set of defensive controls. The following areas are identified for potential extension.

**Two-factor authentication (2FA).** The database schema includes a `two_factor_auth` table in anticipation of this extension. A TOTP-based implementation would allow the project to demonstrate a complete modern authentication flow.

**OAuth 2.0 integration.** Many real-world phishing attacks target OAuth flows rather than direct credential forms. An extension incorporating a simulated OAuth provider would expand the scope of the simulation significantly.

**Automated security testing.** The current test coverage is manual. Integration of PHPUnit for unit testing and OWASP ZAP for automated vulnerability scanning would bring the project closer to production-grade security assurance practices.

**Multi-language support.** Localizing the frontend and educational content would extend the platform's applicability to non-English-speaking educational contexts.

---

## References

OWASP Foundation. (2021). *OWASP Top Ten*. https://owasp.org/www-project-top-ten/

PHP Group. (2024). *PHP Security Manual*. https://www.php.net/manual/en/security.php

Mozilla Foundation. (2024). *Mozilla Web Security Guidelines*. https://infosec.mozilla.org/

Martin, R. C. (2008). *Clean Code: A Handbook of Agile Software Craftsmanship*. Prentice Hall.

Vishwanath, A., et al. (2011). Why do people get phished? Testing individual differences in phishing vulnerability within an integrated information processing model. *Decision Support Systems*, 51(3), 576–586.

---

## Installation

See `SETUP.md` for complete installation and deployment instructions.

Quick-start summary:

```bash
mysql -u root -p < database/phishing_educational.sql
cp config.php.example config.php
chmod 755 logs/ temp/
php -S localhost:8080
```

Access at `http://localhost:8080/frontend/warning.html`. Use only test credentials (`demo_user` / `test123`) during any demonstration session.

---

**Last updated:** 2024
**License:** Educational use only. Deployment against uninformed users is prohibited.