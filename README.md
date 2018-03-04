# Arcanist HTMLHINT Linter

Lints html files using htmlhint.  Developed at BisManOnline.com

## Prerequisites

Install htmlhint.   You can do this in a variety of ways, we use npm, so:

```
npm install htmlhint -g htmlhint
```

## Installation

Clone the repo somewhere locally

```
git clone https://github.com/BisManOnline/arcanist-htmlhint-linter.git
```

Add to your .arcconfig files
```
{
	"phabricator.uri" : "http://phab.yourcompany.com",
	"load" : [
		"/path/to/where/you/put/it/htmlhintlinter/src"
	]
}
```

## Usage

Configure the linter in .arclint

```
{
 "linters": {
    "htmlhint" : {
      "type" : "htmlhint",
      "include": "(\\.html$)"
    }
  }
}
```

## Notes

We developed this, kind of quickly, just for our use.  If you can improve on it...Please do.


