**NEEDS ASSESSMENT DOCUMENT**

**Proposed Student Cumulative Record Management System**

University of La Salette, Inc. — Human Development Center

# 1. Document Purpose

This needs assessment organizes information from the interview with the Director of the ULS Human Development Center and the project team's follow-up selections. It identifies the current process, problems, proposed system requirements, users, privacy needs, technical considerations, and items that still require confirmation. It is intended to support requirements analysis and initial project planning.

# 2. Source and Respondent Information

- Organization: University of La Salette, Inc. (ULS), Human Development Center.

- Interview date and time recorded in the transcript: October 6, 1:30 PM. The transcript does not specify a year.

- Respondent: Jhoanna Marie Guitering Reyes, Director, ULS Human Development Center.

- Interviewers listed in the transcript: Jewel Anne Julian, Adrian Santiago, and Armandt Marcos.

- Basis of this document: Interview transcript and the project team's answers to five rounds of multiple-choice clarification questions.

# 3. Executive Summary

The Human Development Center currently manages student cumulative records manually using paper forms and physical filing. During enrollment, completing a form is estimated to take 10–15 minutes, and the large volume of records makes filing and retrieval time-consuming. The director reported that, during busy enrollment periods, staff may lose track of whether a student has submitted a cumulative record. The transcript refers to a population of more than 6,000 students/records.

The project team's preferred first version is an online student cumulative record management system. Students would submit their own forms online, while authorized Human Development Center personnel review and monitor submissions. The proposed system would provide centralized storage, search and filtering by college/department and year level, individual accounts, role-based access, submission notifications, submission tracking, and authorized aggregate summaries of psychological concerns. Because the records include sensitive mental-health and family-related information, confidentiality and access restrictions are central requirements.

Several matters remain unresolved, including integration with the university's existing system, the feasibility and meaning of offline support for an online system, final access-role details, the approved budget, and the implementation schedule.

# 4. Existing Process and Identified Problems

## 4.1 Current process — confirmed by interview

- Student cumulative records are currently handled manually using pen-and-paper forms and physical records.

- During enrollment, filling out the required form takes an estimated 10–15 minutes.

- The director described handling more than 6,000 students/records, contributing to the volume of paperwork.

- Staff sometimes have difficulty tracking whether a student has submitted their cumulative record during busy enrollment periods.

- Finding an individual record may require staff to search through physical files one by one.

## 4.2 Main problems

- Time-consuming form completion, filing, and retrieval.

- Large amounts of physical paperwork and storage requirements.

- Risk of losing track of submitted cumulative records during enrollment.

- Sensitive student information may be exposed when physical files are kept in drawers or rooms that are not sufficiently secured.

# 5. Project Goal and Scope

## 5.1 Goal

To propose a practical, user-friendly system that computerizes student cumulative records, improves record retrieval and submission monitoring, reduces dependence on physical paperwork, and strengthens the confidentiality and security of student information.

## 5.2 Proposed scope for the first version — project-team choices

- Online student cumulative record form submission.

- Centralized digital storage of student cumulative records.

- Search and filtering by college/department and year level.

- User login and access control.

- Counselor access restricted to assigned college(s), with director-level access across colleges.

- Submission notifications and a tracker showing submitted and missing records.

- Authorized aggregate summaries of selected psychological concerns, with individual records restricted.

## 5.3 Outside the current scope unless later approved

- Replacing the entire university information system.

- Managing tuition, payments, payroll, attendance, or unrelated university operations.

- Providing unrestricted access to all student information for all university employees.

# 6. Proposed Functional Requirements

| **ID**    | **Requirement**               | **Description**                                                                                                                                     | **Basis / status**                                                                                   |
|-----------|-------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------|
| **FR-01** | Online cumulative record form | Students should be able to complete and submit their own cumulative record form online.                                                             | Project-team choice; aligns with the director's interest in online access and student-entered data.  |
| **FR-02** | Centralized record storage    | The system should store submitted cumulative records in a centralized digital repository.                                                           | Supported by the director's stated need to store student data and reduce physical records.           |
| **FR-03** | Search and filtering          | Authorized staff should be able to search and filter records by college/department and year level.                                                  | Supported by the interview; specific filters to be confirmed during design.                          |
| **FR-04** | Submission monitoring         | The system should help staff identify submitted and missing cumulative records.                                                                     | Project-team choice based on the director's reported monitoring difficulty.                          |
| **FR-05** | Submission notification       | The system should notify authorized staff when a new cumulative record is submitted.                                                                | Explicitly discussed in the interview.                                                               |
| **FR-06** | User authentication           | The system should require individual user accounts and passwords.                                                                                   | The director expressed a preference for login/password protection.                                   |
| **FR-07** | Role-based access             | Access should be restricted by role and counselor assignment; the director should be able to access records across colleges.                        | Based on the project's final selection and the director's interview; confirm the exact role matrix.  |
| **FR-08** | Record editing                | Students should be able to edit their own information according to defined rules; staff should review and monitor records within their permissions. | Based on the project's selections; exact edit windows and approval workflow remain unspecified.      |
| **FR-09** | Aggregate concern reports     | The system should provide authorized staff with summary counts for selected psychological concerns, such as anxiety or depression.                  | Explicitly discussed in the interview; report definitions and privacy controls require confirmation. |
| **FR-10** | Confidential record access    | Individual psychological and family-related responses should only be visible to appropriately authorized personnel.                                 | Strongly supported by the interview and project selections.                                          |
| **FR-11** | Data backup and recovery      | The project should consider regular backups and a way to recover records.                                                                           | Project-team proposed safeguard; backup frequency and recovery objectives are not yet defined.       |

# 7. Users and Access Control

The following is the proposed access model based on the project's final answers. It must be reviewed and approved by the Human Development Center before implementation.

| **User role**                     | **Proposed permissions**                                                                                                       | **Restrictions**                                                                                |
|-----------------------------------|--------------------------------------------------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------|
| Student / enrollee                | Complete and submit their own cumulative record; edit their own information according to the rules established for the system. | Must not access other students' records or confidential reports.                                |
| Counselor / authorized staff      | Review and monitor records for assigned college(s); access only information needed for their duties.                           | Access outside assigned college(s) should be blocked.                                           |
| Human Development Center director | Access records across all colleges and oversee counselor assignments and monitoring.                                           | Broad access must remain protected by individual authentication and confidentiality safeguards. |
| Other university department       | Request information through the Human Development Center and follow the center's required coordination/request process.        | No default browsing access to confidential records.                                             |

Important clarification: In Part 3, an earlier answer selected every counselor as having access to all colleges. In Part 5, the project team selected the director as the only role with cross-college access, with counselors limited to assigned colleges. This document uses the Part 5 selection as the current proposed model, but the Human Development Center should confirm it before implementation.

# 8. Data Requirements and Privacy

## 8.1 Data to be managed

The director offered a sample cumulative record form during the interview. The transcript does not reproduce the complete field list from that form. Therefore, the exact data fields should be taken from the approved form and validated with the Human Development Center rather than inferred here.

The interview specifically indicates that the records may include:

- Student cumulative record details supplied through the form.

- Psychological concern responses, including selections related to anxiety or depression.

- Family-related information and descriptions.

- College/department and year-level information needed to organize records.

## 8.2 Privacy and security requirements

- Require individual accounts and passwords for authorized users.

- Apply role-based access and counselor-to-college assignment restrictions.

- Keep psychological and family-related information confidential.

- Restrict individual-level concern responses while permitting approved aggregate summaries.

- Use the Human Development Center's coordination/request process for information requests from other departments.

- Consider regular backups and recovery procedures as a proposed safeguard.

- Define how student edits, staff review, and record corrections will be handled before development.

The director mentioned confidentiality obligations and the Data Privacy Act in the interview. The specific institutional procedures, retention rules, consent language, and security controls should be confirmed with the university's authorized personnel; they are not fully specified in the transcript.

# 9. Non-Functional Requirements

| **Quality area**            | **Requirement / status**                                                                                                                  |
|-----------------------------|-------------------------------------------------------------------------------------------------------------------------------------------|
| Usability                   | The system should be easy to use and should not require intensive training.                                                               |
| Performance / efficiency    | The system should reduce time spent searching for and monitoring cumulative records; exact performance targets are not yet defined.       |
| Confidentiality             | Sensitive records must be protected from unauthorized access.                                                                             |
| Access control              | Users should only access records appropriate to their roles and assignments.                                                              |
| Availability / connectivity | The current preference is online access, including off-campus student submission. Offline operation remains a feasibility question.       |
| Compatibility               | The system should work with the Human Development Center's existing computers.                                                            |
| Recoverability              | Regular backups and record recovery should be considered; technical targets remain to be decided.                                         |
| Maintainability             | The first version should prioritize practical core functions over unnecessary features; maintenance ownership has not yet been specified. |

# 10. Technical Requirements and Constraints

- Operating mode preference: Online system accessible through the internet, including off-campus submission by students.

- Hardware: Compatible with the center's existing computers.

- University system integration: The project team prefers a separate system, but the interview states that integration needs clarification with the university's system administrator. This remains unresolved.

- Offline support: The project team would like offline work if feasible. This must be evaluated against the online-first requirement and the expected data synchronization approach.

- Budget: No budget has been allocated yet. The director indicated that funding might be requested subject to further discussion and approval.

- Timeline: The director did not specify a required completion date. The project team selected that no fixed deadline is currently required; a development and review schedule should still be agreed by the project stakeholders.

- Staffing: At the time of the interview, the director said one counselor remained after four staff members resigned and that replacements were being processed. The final staffing distribution may change and should be confirmed before configuring counselor assignments.

# 11. Project Success Criteria

The project team's selected indicators of success are:

- Faster enrollment-related record processing.

- Easier retrieval of student cumulative records.

- Better organization and monitoring of submitted and missing records.

- Improved confidentiality and security compared with paper files.

- A practical interface that students and authorized staff can use with minimal training.

These are qualitative success criteria. Specific measurable targets (for example, a target retrieval time or acceptable notification delay) have not yet been agreed and should not be treated as established facts.

# 12. Confirmed Interview Findings vs. Project-Team Choices

| **Finding / requirement**                                                                                                                     | **Status**                                                                       |
|-----------------------------------------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------|
| Current paper-based process; estimated 10–15 minutes to complete a form; more than 6,000 records/students; submission tracking difficulties   | Confirmed in interview                                                           |
| Need to computerize cumulative records and reduce physical paper storage and manual searching                                                 | Confirmed in interview                                                           |
| Interest in online or computerized records, student data entry, staff access, college/year organization, notifications, and concern summaries | Discussed in interview; exact workflows to be specified                          |
| Online-only system with off-campus student submission                                                                                         | Project-team choice; clarify offline contingency                                 |
| Separate system with no integration                                                                                                           | Project-team preference; university integration decision unresolved              |
| Students fill out their own forms online                                                                                                      | Project-team confirmation of proposed workflow; aligns with interview discussion |
| Director has cross-college access; counselors limited to assigned colleges                                                                    | Project-team final selection and consistent with director's description          |
| Submission status tracker, backups/recovery, and detailed role-based controls                                                                 | Project-team proposed requirements; confirm details with stakeholders            |
| Fixed budget, exact completion date, exact data fields, retention policy, and technical hosting details                                       | Not established in the transcript                                                |

# 13. Unresolved Questions for Follow-Up

- What exact fields from the existing cumulative record form must be included in the system?

- Will the system remain fully separate from the university information system, or is any integration required?

- Is offline operation necessary, or is internet access sufficient? If offline support is required, what must work offline?

- Confirm the access matrix: director, counselors, other authorized staff, and students.

- Can students edit a submitted form at any time, or only until staff review/approval?

- What specific notifications are required, and who should receive them?

- Which aggregate psychological concern reports are needed, who may view them, and how should small groups be handled to avoid identifying students?

- What data backup frequency, recovery procedure, and record retention period should be used?

- Where will the online system be hosted, who will administer it, and who will be responsible for maintenance?

- What budget and implementation/review schedule can be approved?

- What are the final counselor-to-college assignments once staffing is confirmed?

- What university policies, confidentiality agreements, consent wording, and data privacy procedures must the project follow?

# 14. Conclusion

The needs assessment identifies a clear operational need to replace or improve the Human Development Center's manual cumulative-record process. A centralized, searchable system with online student submission, submission monitoring, notifications, and carefully restricted access is the project's proposed direction. The sensitive nature of psychological and family-related information makes privacy and role-based access essential. Before implementation, the team should validate the exact form fields, user permissions, integration and offline needs, hosting and backup arrangements, budget, and project schedule with the Human Development Center and the relevant university personnel.

# Appendix A. Interview Reference

Interview Transcript: ULS Human Development Center. Respondent: Jhoanna Marie Guitering Reyes, Director. Date and time as recorded: October 6, 1:30 PM (year not specified in transcript). Interviewers: Jewel Anne Julian, Adrian Santiago, and Armandt Marcos. This needs assessment paraphrases the transcript and distinguishes direct interview findings from project-team choices and unresolved matters.
