COLOR_WARNING = \033[31m
RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

# do nothing if make is called without target
placeholder:
	@:
.PHONY: placeholder

dev:
	@./etc/docker/bin/dev $(RUN_ARGS)
.PHONY: dev

composer:
	@./etc/docker/bin/composer $(RUN_ARGS)
.PHONY: composer

ci:
	@./bin/qa.sh test $(RUN_ARGS)
.PHONY: ci

%:
	@:
