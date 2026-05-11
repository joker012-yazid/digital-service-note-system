# Subagent Orchestration Playbook

This file explains how the main agent should delegate work to subagents.

## General Rule

Use subagents only when the task benefits from parallel analysis or specialized review.

Do not spawn subagents for very small tasks, such as:
- fixing one typo,
- changing one line of text,
- updating a simple link,
- changing a simple color,
- editing one static sentence.

## Available Agents

### code_mapper

Use for:
- understanding unfamiliar codebases,
- finding relevant files,
- tracing current behavior,
- identifying implementation scope.

Must not:
- edit files,
- propose broad rewrites.

### frontend_engineer

Use for:
- landing pages,
- UI components,
- forms,
- responsive layout,
- client-side bugs,
- visual polish.

Must not:
- change backend logic unless explicitly required.

### backend_engineer

Use for:
- API routes,
- database logic,
- validation,
- business rules,
- authentication/authorization when specified.

Must not:
- add new database fields, logging, roles, or services unless required.

### bug_fixer

Use for:
- bug reports,
- errors,
- broken behavior,
- failing builds,
- failing tests.

Must:
- find root cause,
- fix minimally,
- verify.

### reviewer

Use for:
- reviewing changes before final response,
- checking PRD compliance,
- checking regression risk,
- checking repeated mistakes from tasks/lessons.md.

Must not:
- edit files.

### tester

Use for:
- test strategy,
- build/lint/type-check,
- manual smoke test,
- checking if done is actually done.

Must:
- report failed checks honestly.

## Recommended Workflow

For non-trivial tasks:

1. Main agent reads AGENTS.md, PRD.md, and tasks/lessons.md.
2. Main agent writes plan to tasks/todo.md.
3. Spawn code_mapper to inspect relevant files.
4. Spawn specialist agent:
   - frontend_engineer for UI/client work,
   - backend_engineer for server/data work,
   - bug_fixer for bugs.
5. Spawn tester or reviewer before final completion.
6. Main agent consolidates all findings.
7. Main agent updates tasks/todo.md with Review / Result.
8. If user corrected a mistake, update tasks/lessons.md.

## Important Coordination Rule

Avoid multiple write-heavy agents editing the codebase at the same time.

Preferred pattern:
- read-only agents investigate first,
- one implementation agent edits,
- reviewer/tester verifies after.